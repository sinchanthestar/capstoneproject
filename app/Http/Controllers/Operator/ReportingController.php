<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedules;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportingController extends Controller
{
    // Recap harian
    public function daily(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        
        $schedules = Schedules::with(['user', 'shift', 'attendance'])
            ->whereDate('schedule_date', $date)
            ->get();
        
        $data = [];
        foreach ($schedules as $schedule) {
            $attendance = $schedule->attendance;
            $data[] = [
                'user' => $schedule->user,
                'shift' => $schedule->shift,
                'schedule_date' => $schedule->schedule_date,
                'check_in_time' => $attendance?->check_in_time,
                'check_out_time' => $attendance?->check_out_time,
                'status' => $attendance?->status ?? 'alpha',
                'is_late' => $attendance?->is_late ?? false
            ];
        }
        
        $summary = [
            'total_scheduled' => $schedules->count(),
            'present' => Attendance::whereDate('created_at', $date)->where('is_late', false)->count(),
            'late' => Attendance::whereDate('created_at', $date)->where('is_late', true)->count(),
            'absent' => $schedules->count() - Attendance::whereDate('created_at', $date)->count()
        ];
        
        return view('operator.reports.daily', [
            'date' => $date,
            'data' => $data,
            'summary' => $summary
        ]);
    }

    // Recap mingguan
    public function weekly(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfWeek()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfWeek()->toDateString());
        
        $users = User::where('role', 'User')->get();
        $data = [];
        
        foreach ($users as $user) {
            $schedules = Schedules::where('user_id', $user->id)
                ->whereBetween('schedule_date', [$startDate, $endDate])
                ->pluck('id');
            
            $attended = Attendance::whereIn('schedule_id', $schedules)->count();
            $total = $schedules->count();
            
            $data[] = [
                'user' => $user,
                'total_scheduled' => $total,
                'attended' => $attended,
                'absent' => max(0, $total - $attended),
                'percentage' => $total > 0 ? round(($attended / $total) * 100, 2) : 0
            ];
        }
        
        return view('operator.reports.weekly', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data
        ]);
    }

    // Recap bulanan
    public function monthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        
        $users = User::where('role', 'User')->get();
        $data = [];
        
        foreach ($users as $user) {
            $schedules = Schedules::where('user_id', $user->id)
                ->whereBetween('schedule_date', [$startDate, $endDate])
                ->pluck('id');
            
            $attended = Attendance::whereIn('schedule_id', $schedules)->count();
            $late = Attendance::whereIn('schedule_id', $schedules)->where('is_late', true)->count();
            $total = $schedules->count();
            
            $data[] = [
                'user' => $user,
                'total_scheduled' => $total,
                'attended' => $attended,
                'late' => $late,
                'absent' => max(0, $total - $attended),
                'percentage' => $total > 0 ? round(($attended / $total) * 100, 2) : 0
            ];
        }
        
        return view('operator.reports.monthly', [
            'month' => $month,
            'year' => $year,
            'data' => $data
        ]);
    }
}
