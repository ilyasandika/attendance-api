<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password'
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class, "user_id", "id");
    }
}
