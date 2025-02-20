<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDay extends Model
{
    use HasFactory;

    protected $fillable = [
        "shift_id",
        "name",
        "check_in",
        "check_out",
        "break_start",
        "break_end",
        "is_off"
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
