<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "default"
    ];

    protected static function booted()
    {
        static::creating(function ($shift) {
            if ($shift->default) {
                static::where('default', true)->update(['default' => false]);
            }
        });

        static::updating(function ($shift) {
            if ($shift->default) {
                static::where('default', true)
                    ->where('id', '!=', $shift->id)
                    ->update(['default' => false]);
            }
        });
    }
    public function schedule()
    {
        return $this->hasMany(Schedule::class, "shift_id", "id");
    }

    public function shiftDay()
    {
        return $this->hasMany(ShiftDay::class, "shift_id", "id");
    }
}
