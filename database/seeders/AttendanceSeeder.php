<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $scheduleId = 1;
        $latitude = -6.1750985;
        $longitude = 106.7873879;

        $startDate = Carbon::create(2025, 6, 1);
        $endDate   = Carbon::create(2025, 9, 26);

        $records = [];

        for ($userId = 1; $userId <= 15; $userId++) {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

                // skip weekend
                if ($date->isWeekend()) {
                    continue;
                }
                $isPresent = rand(0, 1);

                if ($isPresent) {
                    $checkIn = $date->copy()->setTime(7, rand(-10, 30));
                    $checkOut = $date->copy()->setTime(16, rand(-30, 30));

                    $checkInStatus = $checkIn->lte($date->copy()->setTime(7, 0))
                        ? 'on time'
                        : 'late';

                    $checkOutStatus = $checkOut->lt($date->copy()->setTime(16, 0))
                        ? 'early leave'
                        : 'on time';

                    $records[] = [
                        'user_id' => $userId,
                        'schedule_id' => $scheduleId,
                        'date' => $checkIn->copy()->startOfDay()->timestamp,
                        'start_time' => '07:00',
                        'end_time' => '16:00',
                        'duration' => max(0, ($checkOut->timestamp - $checkIn->timestamp) / 60),
                        'late_minutes' => max(0, $checkIn->diffInMinutes($date->copy()->setTime(7, 0), false)),
                        'early_leave_minutes' => max(0, $date->copy()->setTime(16, 0)->diffInMinutes($checkOut, false)),
                        'overtime_minutes' => max(0, $checkOut->diffInMinutes($date->copy()->setTime(16, 0), false)),
                        'check_in_time' => $checkIn->timestamp,
                        'check_out_time' => $checkOut->timestamp,
                        'check_in_latitude' => $latitude,
                        'check_in_longitude' => $longitude,
                        'check_out_latitude' => $latitude,
                        'check_out_longitude' => $longitude,
                        'check_in_address' => 'Jl. Contoh No.1, Jakarta',
                        'check_out_address' => 'Jl. Contoh No.1, Jakarta',
                        'check_in_status' => $checkInStatus,
                        'check_out_status' => $checkOutStatus,
                        'check_in_outside_location' => false,
                        'check_out_outside_location' => false,
                        'check_in_comment' => null,
                        'check_out_comment' => null,
                        'auto_checkout' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    $records[] = [
                        'user_id' => $userId,
                        'schedule_id' => $scheduleId,
                        'date' => $date->copy()->startOfDay()->timestamp,
                        'start_time' => '07:00',
                        'end_time' => '16:00',
                        'duration' => 0,
                        'late_minutes' => 0,
                        'early_leave_minutes' => 0,
                        'overtime_minutes' => 0,
                        'check_in_time' => null,
                        'check_out_time' => $date->copy()->setTime(16, 0)->timestamp, // auto checkout jam 16:00
                        'check_in_latitude' => null,
                        'check_in_longitude' => null,
                        'check_out_latitude' => null,
                        'check_out_longitude' => null,
                        'check_in_address' => null,
                        'check_out_address' => null,
                        'check_in_status' => 'absent',
                        'check_out_status' => 'absent',
                        'check_in_outside_location' => true,
                        'check_out_outside_location' => true,
                        'check_in_comment' => null,
                        'check_out_comment' => 'Auto checkout (absent)',
                        'auto_checkout' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('attendances')->insert($records);
    }
}
