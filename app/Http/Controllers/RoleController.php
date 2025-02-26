<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function getRoleList()
    {
        return response()->json([
            "status" => 200,
            "message" => "SUCCESS",
            "data" => Role::get()
        ]);
    }
}
