<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                "name" => "Kepala Pesantren",
                "description" => "Kepala Pesantren",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Ustadz Tahfidz",
                "description" => "Ustadz Tahfidz",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Guru Mata Pelajaran",
                "description" => "Guru Mata Pelajaran",
                "created_at" => now(),
                "updated_at" => now()
            ],
        ]);
    }
}
