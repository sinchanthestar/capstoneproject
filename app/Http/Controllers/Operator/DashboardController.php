<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Schedules;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $operator = Auth::user();
        $now = Carbon::now();
        $selectedMonth = $request->input('selected_month', $now->month);
        $selectedYear = $request->input('selected_year', $now->year);
        
        // Create date from selected month and year
        $monthDate = Carbon::create($selectedYear, $selectedMonth, 1);
        $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();
        
        // Statistics
        $totalUsers = User::where('role', 'User')->count();
        $totalSchedules = Schedules::whereBetween('schedule_date', [$startOfMonth, $endOfMonth])->count();
        $totalAttendances = Attendance::whereBetween('created_at', [$monthDate->copy()->startOfDay(), $monthDate->copy()->endOfMonth()->endOfDay()])->count();
        
        // Today's attendance
        $today = $now->copy()->startOfDay();
        $todaySchedules = Schedules::whereDate('schedule_date', $today)->count();
        $todayStats = $this->getTodayAttendanceStats();
        
        // Permission requests (pending)
        $pendingPermissions = Permissions::where('status', 'pending')->count();
        
        // Get monthly attendance data
        $attendanceData = $this->getMonthlyAttendanceData($startOfMonth, $endOfMonth);
        
        // Get top absent employees
        $topAbsentEmployees = $this->getTopAbsentEmployees($startOfMonth, $endOfMonth, 5);
        
        // Get top late employees
        $topLateEmployees = $this->getTopLateEmployees($startOfMonth, $endOfMonth, 5);
        
        // Get permission requests summary
        $permissionRequests = Permissions::with('user', 'schedule')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get recent attendances
        $recentAttendances = Attendance::with(['user', 'schedule.shift'])
            ->whereDate('created_at', $today)
            ->whereNotNull('check_in_time')
            ->orderBy('check_in_time', 'desc')
            ->limit(10)
            ->get();
        
        // Shift distribution for month
        $monthlySchedules = Schedules::with('shift')
            ->whereBetween('schedule_date', [$startOfMonth, $endOfMonth])
            ->get();
        
        $shiftPagi = $monthlySchedules->filter(fn($s) => optional($s->shift)->category === 'Pagi')->count();
        $shiftSiang = $monthlySchedules->filter(fn($s) => optional($s->shift)->category === 'Siang')->count();
        $shiftMalam = $monthlySchedules->filter(fn($s) => optional($s->shift)->category === 'Malam')->count();
        
        return view('operator.dashboard', [
            'operator' => $operator,
            'totalUsers' => $totalUsers,
            'totalSchedules' => $totalSchedules,
            'totalAttendances' => $totalAttendances,
            'todaySchedules' => $todaySchedules,
            'todayStats' => $todayStats,
            'pendingPermissions' => $pendingPermissions,
            'attendanceData' => $attendanceData,
            'topAbsentEmployees' => $topAbsentEmployees,
            'topLateEmployees' => $topLateEmployees,
            'permissionRequests' => $permissionRequests,
            'recentAttendances' => $recentAttendances,
            'shiftPagi' => $shiftPagi,
            'shiftSiang' => $shiftSiang,
            'shiftMalam' => $shiftMalam,
            'currentMonth' => $monthDate->format('F Y'),
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'currentDate' => $now->format('l, d F Y')
        ]);
    }
    
    private function getTodayAttendanceStats()
    {
        $today = Carbon::today()->toDateString();
        
        $schedules = Schedules::whereDate('schedule_date', $today)->get();
        $totalSchedules = $schedules->count();
        
        $hadir = 0;
        $telat = 0;
        $izin = 0;
        $alpha = 0;
        
        foreach ($schedules as $schedule) {
            $attendance = Attendance::where('schedule_id', $schedule->id)->first();
            $permission = Permissions::where('schedule_id', $schedule->id)
                ->where('status', 'approved')
                ->first();
            
            if ($permission) {
                $izin++;
            } elseif ($attendance) {
                if ($attendance->is_late) {
                    $telat++;
                } else {
                    $hadir++;
                }
            } else {
                $alpha++;
            }
        }
        
        return [
            'total' => $totalSchedules,
            'hadir' => $hadir,
            'telat' => $telat,
            'izin' => $izin,
            'alpha' => $alpha
        ];
    }
    
    private function getMonthlyAttendanceData($startDate, $endDate)
    {
        $data = [];
        
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            
            $schedules = Schedules::whereDate('schedule_date', $dateString)->get();
            
            $hadir = 0;
            $telat = 0;
            $izin = 0;
            $alpha = 0;
            
            foreach ($schedules as $schedule) {
                $attendance = Attendance::where('schedule_id', $schedule->id)->first();
                $permission = Permissions::where('schedule_id', $schedule->id)
                    ->where('status', 'approved')
                    ->first();
                
                if ($permission) {
                    $izin++;
                } elseif ($attendance) {
                    if ($attendance->is_late) {
                        $telat++;
                    } else {
                        $hadir++;
                    }
                } else {
                    $alpha++;
                }
            }
            
            $data[] = [
                'date' => $dateString,
                'day' => (int)$date->format('d'),
                'hadir' => $hadir,
                'telat' => $telat,
                'izin' => $izin,
                'alpha' => $alpha
            ];
        }
        
        return $data;
    }
    
    private function getTopAbsentEmployees($startDate, $endDate, $limit = 5)
    {
        $users = User::where('role', 'User')->get();
        $absentCounts = [];
        
        foreach ($users as $user) {
            $schedules = Schedules::where('user_id', $user->id)
                ->whereBetween('schedule_date', [$startDate, $endDate])
                ->pluck('id');
            
            $absents = Attendance::whereIn('schedule_id', $schedules)->count() === 0 
                ? Schedules::whereIn('id', $schedules)->count() 
                : Schedules::whereIn('id', $schedules)->count() - Attendance::whereIn('schedule_id', $schedules)->count();
            
            if ($absents > 0) {
                $absentCounts[$user->id] = [
                    'user' => $user,
                    'count' => $absents
                ];
            }
        }
        
        uasort($absentCounts, fn($a, $b) => $b['count'] <=> $a['count']);
        
        return collect(array_slice($absentCounts, 0, $limit));
    }
    
    private function getTopLateEmployees($startDate, $endDate, $limit = 5)
    {
        $users = User::where('role', 'User')->get();
        $lateCounts = [];
        
        foreach ($users as $user) {
            $schedules = Schedules::where('user_id', $user->id)
                ->whereBetween('schedule_date', [$startDate, $endDate])
                ->pluck('id');
            
            $lates = Attendance::whereIn('schedule_id', $schedules)
                ->where('is_late', true)
                ->count();
            
            if ($lates > 0) {
                $lateCounts[$user->id] = [
                    'user' => $user,
                    'count' => $lates
                ];
            }
        }
        
        uasort($lateCounts, fn($a, $b) => $b['count'] <=> $a['count']);
        
        return collect(array_slice($lateCounts, 0, $limit));
    }
}

