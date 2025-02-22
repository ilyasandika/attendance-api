<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shift = Shift::create([
            "name" => "5 Days",
            "description" => "Normal 5 Days Working Hours",
            "created_at" => now(),
            "updated_at" => now()
        ]);

        $shift->ShiftDay()->createMany([
            [
                "name" => "monday",
                "check_in" => "07:00:00",
                "check_out" => "16:00:00",
                "break_start" => "12:00:00",
                "break_end" => "13:00:00",
                "is_off" => 0,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "tuesday",
                "check_in" => "07:00:00",
                "check_out" => "16:00:00",
                "break_start" => "12:00:00",
                "break_end" => "13:00:00",
                "is_off" => 0,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "wednesday",
                "check_in" => "07:00:00",
                "check_out" => "16:00:00",
                "break_start" => "12:00:00",
                "break_end" => "13:00:00",
                "is_off" => 0,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "thursday",
                "check_in" => "07:00:00",
                "check_out" => "16:00:00",
                "break_start" => "12:00:00",
                "break_end" => "13:00:00",
                "is_off" => 0,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "friday",
                "check_in" => "07:00:00",
                "check_out" => "16:00:00",
                "break_start" => "12:00:00",
                "break_end" => "13:00:00",
                "is_off" => 0,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "saturday",
                "check_in" => "07:00:00",
                "check_out" => "16:00:00",
                "break_start" => "12:00:00",
                "break_end" => "13:00:00",
                "is_off" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "sunday",
                "check_in" => "07:00:00",
                "check_out" => "16:00:00",
                "break_start" => "12:00:00",
                "break_end" => "13:00:00",
                "is_off" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ]
        ]);
    }
}
