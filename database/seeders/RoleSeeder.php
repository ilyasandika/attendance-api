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
                "name" => "Helpdesk Support",
                "description" => "Helpdesk Support Junior",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "System Administrator",
                "description" => "Manages IT infrastructure and security",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Software Engineer",
                "description" => "Develops and maintains software applications",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Project Manager",
                "description" => "Leads project teams and ensures successful delivery",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "IT Support Specialist",
                "description" => "Provides technical support for users",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
