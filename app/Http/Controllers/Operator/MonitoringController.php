<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedules;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    // Monitoring realtime
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $shiftFilter = $request->input('shift');
        
        $schedules = Schedules::with(['user', 'shift', 'attendance'])
            ->whereDate('schedule_date', $date)
            ->when($shiftFilter, fn($q) => $q->whereHas('shift', fn($sq) => $sq->where('id', $shiftFilter)))
            ->orderBy('schedule_date')
            ->get();
        
        $checkedIn = $schedules->filter(fn($s) => $s->attendance)->values();
        $notCheckedIn = $schedules->filter(fn($s) => !$s->attendance)->values();
        
        $allShifts = Shift::all();
        
        $stats = [
            'total' => $schedules->count(),
            'checked_in' => $checkedIn->count(),
            'not_checked_in' => $notCheckedIn->count(),
            'percentage' => $schedules->count() > 0 ? round(($checkedIn->count() / $schedules->count()) * 100, 2) : 0
        ];
        
        return view('operator.monitoring.index', [
            'schedules' => $schedules,
            'checkedIn' => $checkedIn,
            'notCheckedIn' => $notCheckedIn,
            'date' => $date,
            'allShifts' => $allShifts,
            'shiftFilter' => $shiftFilter,
            'stats' => $stats
        ]);
    }
}
