<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "default"
    ];

    protected static function booted()
    {
        static::creating(function ($role) {
            if ($role->default) {
                static::where('default', true)->update(['default' => false]);
            }
        });

        static::updating(function ($role) {
            if ($role->default) {
                static::where('default', true)
                    ->where('id', '!=', $role->id)
                    ->update(['default' => false]);
            }
        });
    }

    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }
}
