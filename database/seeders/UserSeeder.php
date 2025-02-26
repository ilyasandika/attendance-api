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
        // Admin
        $admin = User::create([
            "employee_id" => "001",
            "email" => "admin@gmail.com",
            "password" => Hash::make("admin123"), // Bisa diganti dengan password yang lebih aman
            "role" => "admin",
            "status" => 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $admin->Profile()->create([
            'name' => "Admin User",
            'role_id' => 1, // Pastikan ID role admin ada
            'department_id' => 1, // Pastikan ID department admin ada
            'gender' => "male",
            'birth_date' => strtotime("1990-01-01"), // Lebih readable daripada angka langsung
            'phone_number' => "081234567890",
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $admin->Schedule()->create([
            'shift_id' => 1, // Pastikan shift dengan ID ini ada
            'location_id' => 1, // Pastikan lokasi dengan ID ini ada
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        // Employees (3 user)
        $employees = [
            [
                "employee_id" => "002",
                "email" => "employee1@gmail.com",
                "name" => "Employee One",
                "role_id" => 2,
                "department_id" => 2,
                "shift_id" => 2,
                "location_id" => 2,
            ],
            [
                "employee_id" => "003",
                "email" => "employee2@gmail.com",
                "name" => "Employee Two",
                "role_id" => 3,
                "department_id" => 3,
                "shift_id" => 3,
                "location_id" => 3,
            ],
            [
                "employee_id" => "004",
                "email" => "employee3@gmail.com",
                "name" => "Employee Three",
                "role_id" => 4,
                "department_id" => 4,
                "shift_id" => 1, // Mengulang shift pertama
                "location_id" => 4,
            ],
        ];

        foreach ($employees as $emp) {
            $user = User::create([
                "employee_id" => $emp["employee_id"],
                "email" => $emp["email"],
                "password" => Hash::make("password123"),
                "role" => "employee",
                "status" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            $user->Profile()->create([
                'name' => $emp["name"],
                'role_id' => $emp["role_id"],
                'department_id' => $emp["department_id"],
                'gender' => "male",
                'birth_date' => strtotime("1995-01-01"),
                'phone_number' => "081234567891",
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            $user->Schedule()->create([
                'shift_id' => $emp["shift_id"],
                'location_id' => $emp["location_id"],
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
    }
}
