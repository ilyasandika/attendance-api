<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function getDepartmentList()
    {
        return response()->json([
            "status" => 200,
            "message" => "SUCCESS",
            "data" => Department::get()
        ]);
    }
}
