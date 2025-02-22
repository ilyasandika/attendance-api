<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "employee_id" => "123",
            "email" => "test@@gmail.com",
            "password" => Hash::make("password123"),
            "role" => "admin",
            "status" => 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $user->Profile()->create([
            'name' => "Ilyas Andika",
            'role_id' => 1,
            'department_id' => 1,
            'gender' => "male",
            'birth_date' => 1042502400,
            'phone_number' => "08123456789",
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $user->Schedule()->create([
            'shift_id' => 1,
            'location_id' => 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
