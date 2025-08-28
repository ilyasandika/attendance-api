<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'late_minutes',
        'early_leave_minutes',
        'overtime_minutes',
        'check_in_time',
        'check_out_time',
        'check_in_photo',
        'check_out_photo',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'check_in_address',
        'check_out_address',
        'check_in_status',
        'check_out_status',
        'check_in_outside_location',
        'check_out_outside_location',
        'check_in_comment',
        'check_out_comment',
        'auto_checkout',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}
