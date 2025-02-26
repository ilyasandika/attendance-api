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
        $shifts = [
            [
                "name" => "5 Days",
                "description" => "Normal 5 Days Working Hours",
                "days" => [
                    ["name" => "monday", "is_off" => 0],
                    ["name" => "tuesday", "is_off" => 0],
                    ["name" => "wednesday", "is_off" => 0],
                    ["name" => "thursday", "is_off" => 0],
                    ["name" => "friday", "is_off" => 0],
                    ["name" => "saturday", "is_off" => 1],
                    ["name" => "sunday", "is_off" => 1],
                ]
            ],
            [
                "name" => "6 Days",
                "description" => "6 Days Work with Shorter Hours",
                "days" => [
                    ["name" => "monday", "is_off" => 0],
                    ["name" => "tuesday", "is_off" => 0],
                    ["name" => "wednesday", "is_off" => 0],
                    ["name" => "thursday", "is_off" => 0],
                    ["name" => "friday", "is_off" => 0],
                    ["name" => "saturday", "is_off" => 0],
                    ["name" => "sunday", "is_off" => 1],
                ]
            ],
            [
                "name" => "Shift Night",
                "description" => "Night Shift for IT Support",
                "days" => [
                    ["name" => "monday", "is_off" => 0],
                    ["name" => "tuesday", "is_off" => 0],
                    ["name" => "wednesday", "is_off" => 0],
                    ["name" => "thursday", "is_off" => 0],
                    ["name" => "friday", "is_off" => 0],
                    ["name" => "saturday", "is_off" => 0],
                    ["name" => "sunday", "is_off" => 0],
                ]
            ]
        ];

        foreach ($shifts as $shiftData) {
            $shift = Shift::create([
                "name" => $shiftData["name"],
                "description" => $shiftData["description"],
                "created_at" => now(),
                "updated_at" => now()
            ]);

            $shift->ShiftDay()->createMany(array_map(function ($day) {
                return array_merge($day, [
                    "check_in" => "07:00:00",
                    "check_out" => "16:00:00",
                    "break_start" => "12:00:00",
                    "break_end" => "13:00:00",
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }, $shiftData["days"]));
        }
    }
}
