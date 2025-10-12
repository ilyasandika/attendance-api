<?php

namespace Database\Seeders;


use App\Models\Holiday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $holidays = [
            ['2025-08-17', 'Hari Kemerdekaan'],
            ['2025-09-05', 'Maulid Nabi'],
            ['2025-12-25', 'Natal'],
        ];

        foreach ($holidays as [$date, $name]) {
            Holiday::updateOrCreate(
                ['date' => $date],
                ['name' => $name]
            );
        }
    }
}
