<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedules;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Lihat daftar absensi hari ini
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $shiftId = $request->input('shift');
        $search = $request->input('search');
        
        // Get all schedules for the selected date
        $schedules = Schedules::with(['user', 'shift', 'attendance'])
            ->whereDate('schedule_date', $date)
            ->when($shiftId, function ($q) use ($shiftId) {
                $q->where('shift_id', $shiftId);
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
            })
            ->orderBy('schedule_date')
            ->get();
        
        // Separate checked-in and not checked-in
        $checkedIn = [];
        $notCheckedIn = [];
        
        foreach ($schedules as $schedule) {
            if ($schedule->attendance) {
                $checkedIn[] = $schedule->attendance;
            } else {
                $notCheckedIn[] = $schedule;
            }
        }
        
        $allShifts = \App\Models\Shift::all();
        
        return view('operator.attendance.index', [
            'checkedIn' => collect($checkedIn),
            'notCheckedIn' => collect($notCheckedIn),
            'date' => $date,
            'shiftFilter' => $shiftId,
            'allShifts' => $allShifts
        ]);
    }

    // Form input absensi manual
    public function create()
    {
        $schedules = Schedules::with('user', 'shift')
            ->whereDate('schedule_date', Carbon::today())
            ->whereDoesntHave('attendance')
            ->get();
        
        return view('operator.attendance.create', [
            'schedules' => $schedules
        ]);
    }

    // Simpan absensi manual
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,telat,izin,alpha',
            'notes' => 'nullable|string'
        ]);
        
        $schedule = Schedules::findOrFail($validated['schedule_id']);
        
        // Cek apakah user sudah hadir
        $existingAttendance = Attendance::where('schedule_id', $schedule->id)->first();
        if ($existingAttendance) {
            return back()->with('error', 'Absensi untuk jadwal ini sudah ada');
        }
        
        $isLate = false;
        if ($validated['status'] === 'telat') {
            $isLate = true;
        }
        
        // Extract just the date part (YYYY-MM-DD) from schedule_date
        $scheduleDateObj = Carbon::parse($schedule->schedule_date);
        $scheduleDate = $scheduleDateObj->format('Y-m-d');
        
        // Parse time strings to create proper datetime
        $checkInTime = Carbon::createFromFormat('Y-m-d H:i', $scheduleDate . ' ' . $validated['check_in_time']);
        $checkOutTime = $validated['check_out_time'] 
            ? Carbon::createFromFormat('Y-m-d H:i', $scheduleDate . ' ' . $validated['check_out_time'])
            : null;
        
        Attendance::create([
            'user_id' => $schedule->user_id,
            'schedule_id' => $schedule->id,
            'location_id' => null,
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'is_late' => $isLate,
            'status' => $validated['status'],
        ]);
        
        return redirect()->route('operator.attendance.index')->with('success', 'Absensi berhasil ditambahkan');
    }

    // Edit absensi
    public function edit(Attendance $attendance)
    {
        return view('operator.attendance.edit', [
            'attendance' => $attendance
        ]);
    }

    // Update absensi
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'is_late' => 'nullable|in:0,1',
            'status' => 'required|in:hadir,telat,izin,alpha'
        ]);
        
        $schedule = $attendance->schedule;
        
        // Extract just the date part (YYYY-MM-DD) from schedule_date
        $scheduleDateObj = Carbon::parse($schedule->schedule_date);
        $scheduleDate = $scheduleDateObj->format('Y-m-d');
        
        $attendance->update([
            'check_in_time' => Carbon::createFromFormat('Y-m-d H:i', $scheduleDate . ' ' . $validated['check_in_time']),
            'check_out_time' => $validated['check_out_time'] ? Carbon::createFromFormat('Y-m-d H:i', $scheduleDate . ' ' . $validated['check_out_time']) : null,
            'is_late' => (bool) $validated['is_late'],
            'status' => $validated['status']
        ]);
        
        return redirect()->route('operator.attendance.index')->with('success', 'Absensi berhasil diperbarui');
    }

    // Hapus absensi
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Absensi berhasil dihapus');
    }

    // Tandai hadir
    public function markPresent(Request $request)
    {
        $schedule = Schedules::findOrFail($request->schedule_id);
        
        $existingAttendance = Attendance::where('schedule_id', $schedule->id)->first();
        if ($existingAttendance) {
            return back()->with('error', 'Absensi sudah ada');
        }
        
        Attendance::create([
            'user_id' => $schedule->user_id,
            'schedule_id' => $schedule->id,
            'location_id' => null,
            'check_in_time' => now(),
            'is_late' => false,
            'status' => 'hadir'
        ]);
        
        return back()->with('success', 'User ditandai hadir');
    }

    // Tandai izin
    public function markLeave(Request $request)
    {
        $schedule = Schedules::findOrFail($request->schedule_id);
        
        $existingAttendance = Attendance::where('schedule_id', $schedule->id)->first();
        if ($existingAttendance) {
            return back()->with('error', 'Absensi sudah ada');
        }
        
        Attendance::create([
            'user_id' => $schedule->user_id,
            'schedule_id' => $schedule->id,
            'location_id' => null,
            'status' => 'izin'
        ]);
        
        return back()->with('success', 'User ditandai izin');
    }

    // Tandai alpha
    public function markAbsent(Request $request)
    {
        $schedule = Schedules::findOrFail($request->schedule_id);
        
        $existingAttendance = Attendance::where('schedule_id', $schedule->id)->first();
        if ($existingAttendance) {
            return back()->with('error', 'Absensi sudah ada');
        }
        
        Attendance::create([
            'user_id' => $schedule->user_id,
            'schedule_id' => $schedule->id,
            'location_id' => null,
            'status' => 'alpha'
        ]);
        
        return back()->with('success', 'User ditandai alpha');
    }
}
