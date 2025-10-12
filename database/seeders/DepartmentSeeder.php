<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                "name" => "Tenaga Pengajar",
                "description" => "Guru dan Pengajar",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Tenaga Kependidikan",
                "description" => "Staff Kependidikan (Tata Usaha, Kebersihan, Keamanan, dll)",
                "created_at" => now(),
                "updated_at" => now()
            ],
        ]);
    }
}
