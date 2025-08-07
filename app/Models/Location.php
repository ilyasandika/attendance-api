<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "latitude",
        "longitude",
        "radius",
        "default",
        "address"
    ];

    protected static function booted()
    {
        static::creating(function ($location) {
            if ($location->default) {
                static::where('default', true)->update(['default' => false]);
            }
        });

        static::updating(function ($location) {
            if ($location->default) {
                static::where('default', true)
                    ->where('id', '!=', $location->id)
                    ->update(['default' => false]);
            }
        });
    }
    public function schedule()
    {
        return $this->hasMany(Schedule::class, "location_id", "id");
    }
}
