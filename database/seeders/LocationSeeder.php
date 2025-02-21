<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            "name" => "Head Office MII",
            "description" => "Main Office Mitra Integrasi Informatika",
            "latitude" => -6.1750985,
            "longtitude" => 106.7873879,
            "radius" => 100
        ]);
    }
}
