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
            ['2025-01-01', 'Tahun Baru Masehi'],
            ['2025-01-29', 'Isra Miraj'],
            ['2025-03-31', 'Nyepi'],
            ['2025-04-17', 'Hari Raya Idul Fitri'],
            ['2025-04-18', 'Cuti Bersama Idul Fitri'],
            ['2025-05-01', 'Hari Buruh'],
            ['2025-05-29', 'Kenaikan Isa Almasih'],
            ['2025-06-01', 'Hari Lahir Pancasila'],
            ['2025-06-06', 'Hari Raya Idul Adha'],
            ['2025-06-26', 'Tahun Baru Islam'],
            ['2025-08-17', 'Hari Kemerdekaan'],
            ['2025-10-06', 'Maulid Nabi'],
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
