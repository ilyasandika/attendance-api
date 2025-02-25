<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description"
    ];


    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }
}
