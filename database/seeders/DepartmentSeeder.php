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
                "name" => "IT",
                "description" => "Information Technology",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "HR",
                "description" => "Human Resources",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Finance",
                "description" => "Finance Department",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Marketing",
                "description" => "Marketing and Sales",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Operations",
                "description" => "Operations and Logistics",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
