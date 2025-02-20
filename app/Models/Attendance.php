<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'photo_check_in',
        'photo_check_out',
        'latitude_check_in',
        'longitude_check_in',
        'latitude_check_out',
        'longitude_check_out',
        'schedules_id',
        'status',
        'checkin_outside_location',
        'checkout_outside_location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedules_id');
    }
}
