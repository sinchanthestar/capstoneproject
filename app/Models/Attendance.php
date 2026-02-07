<?php

// app/Models/Attendance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'schedule_id', 'location_id', 'status', 'check_in_time', 'check_out_time', 'is_late', 'late_minutes', 'latitude', 'longitude', 'latitude_checkout', 'longitude_checkout'];

    protected $casts = [
        'check_in_time' => 'datetime:H:i:s',
        'check_out_time' => 'datetime:H:i:s',
        'is_late' => 'boolean',
        'late_minutes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    
    public function shift()
    {
        return $this->hasOneThrough(Shift::class, Schedules::class, 'id', 'id', 'schedule_id', 'shift_id');
    }
}
