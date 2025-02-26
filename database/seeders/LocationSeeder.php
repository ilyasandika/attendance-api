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
            [
                "name" => "Head Office MII",
                "description" => "Main Office Mitra Integrasi Informatika",
                "latitude" => -6.1750985,
                "longitude" => 106.7873879,
                "radius" => 100,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Branch Office Jakarta",
                "description" => "Jakarta Branch Office",
                "latitude" => -6.200000,
                "longitude" => 106.816666,
                "radius" => 150,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Branch Office Bandung",
                "description" => "Bandung Branch Office",
                "latitude" => -6.914744,
                "longitude" => 107.609810,
                "radius" => 120,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Branch Office Surabaya",
                "description" => "Surabaya Branch Office",
                "latitude" => -7.257472,
                "longitude" => 112.752090,
                "radius" => 130,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Branch Office Medan",
                "description" => "Medan Branch Office",
                "latitude" => 3.595196,
                "longitude" => 98.672223,
                "radius" => 140,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
