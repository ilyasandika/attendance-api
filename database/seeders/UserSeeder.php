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

        // Employees (15 user)
        $employees = [
            ["employee_id" => "002", "email" => "employee1@gmail.com", "name" => "Employee One", "role_id" => 2, "department_id" => 1, "shift_id" => 2, "location_id" => 2],
            ["employee_id" => "003", "email" => "employee2@gmail.com", "name" => "Employee Two", "role_id" => 3, "department_id" => 2, "shift_id" => 3, "location_id" => 3],
            ["employee_id" => "004", "email" => "employee3@gmail.com", "name" => "Employee Three", "role_id" => 4, "department_id" => 4, "shift_id" => 1, "location_id" => 4],
            ["employee_id" => "005", "email" => "employee4@gmail.com", "name" => "Employee Four", "role_id" => 1, "department_id" => 1, "shift_id" => 2, "location_id" => 1],
            ["employee_id" => "006", "email" => "employee5@gmail.com", "name" => "Employee Five", "role_id" => 2, "department_id" => 4, "shift_id" => 3, "location_id" => 2],
            ["employee_id" => "007", "email" => "employee6@gmail.com", "name" => "Employee Six", "role_id" => 3, "department_id" => 2, "shift_id" => 1, "location_id" => 3],
            ["employee_id" => "008", "email" => "employee7@gmail.com", "name" => "Employee Seven", "role_id" => 4, "department_id" => 4, "shift_id" => 2, "location_id" => 4],
            ["employee_id" => "009", "email" => "employee8@gmail.com", "name" => "Employee Eight", "role_id" => 1, "department_id" => 1, "shift_id" => 3, "location_id" => 1],
            ["employee_id" => "010", "email" => "employee9@gmail.com", "name" => "Employee Nine", "role_id" => 2, "department_id" => 3, "shift_id" => 1, "location_id" => 2],
            ["employee_id" => "011", "email" => "employee10@gmail.com", "name" => "Employee Ten", "role_id" => 3, "department_id" => 1, "shift_id" => 2, "location_id" => 3],
            ["employee_id" => "012", "email" => "employee11@gmail.com", "name" => "Employee Eleven", "role_id" => 4, "department_id" => 1, "shift_id" => 3, "location_id" => 4],
            ["employee_id" => "013", "email" => "employee12@gmail.com", "name" => "Employee Twelve", "role_id" => 1, "department_id" => 1, "shift_id" => 1, "location_id" => 1],
            ["employee_id" => "014", "email" => "employee13@gmail.com", "name" => "Employee Thirteen", "role_id" => 2, "department_id" => 2, "shift_id" => 2, "location_id" => 2],
            ["employee_id" => "015", "email" => "employee14@gmail.com", "name" => "Employee Fourteen", "role_id" => 3, "department_id" => 3, "shift_id" => 3, "location_id" => 3],
            ["employee_id" => "016", "email" => "employee15@gmail.com", "name" => "Employee Fifteen", "role_id" => 4, "department_id" => 4, "shift_id" => 1, "location_id" => 4],
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
