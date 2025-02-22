<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description"
    ];

    public function schedule()
    {
        return $this->hasMany(Schedule::class, "shift_id", "id");
    }

    public function shiftDay()
    {
        return $this->hasMany(ShiftDay::class, "shift_id", "id");
    }
}
