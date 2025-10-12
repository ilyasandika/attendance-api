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
                "name" => "Yayasan Al-Hayah Hayatuna",
                "description" => "Al-Hayah Jakarta Timur",
                "address" => "Jl. Ciliwung No.81 8, RT.8/RW.6, Cililitan, Kec. Kramat jati, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13640",
                "latitude" => -6.2646771,
                "longitude" => 106.8589554,
                "radius" => 100,
                "created_at" => now(),
                "updated_at" => now()
            ],
        ]);
    }
}
