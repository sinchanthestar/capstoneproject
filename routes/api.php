<?php

use App\Models\Schedules;
use Illuminate\Support\Facades\Route;

Route::get('/calendar', function () {
    $schedules = Schedules::with('user', 'shift')->get();

    $events = $schedules->map(function ($schedule) {
        $dateOnly = \Carbon\Carbon::parse($schedule->schedule_date)->format('Y-m-d');
        $startTime = \Carbon\Carbon::parse($schedule->shift->start_time)->format('H:i:s');
        $endTime = \Carbon\Carbon::parse($schedule->shift->end_time)->format('H:i:s');
        
        $start = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateOnly . ' ' . $startTime);
        $end   = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateOnly . ' ' . $endTime);

        if ($end->lt($start)) {
            $end->addDay();
        }

        return [
            'title' => $schedule->user->name . ' - ' . $schedule->shift->name,
            'start' => $start->format('Y-m-d\TH:i:s'),
            'end'   => $end->format('Y-m-d\TH:i:s'),
        ];
    });

    return response()->json($events);
});
