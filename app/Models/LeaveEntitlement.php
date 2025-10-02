<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEntitlement extends Model
{
    use HasFactory;

    protected $table = 'leave_entitlements';
    protected $fillable = [
        'user_id',
        'year',
        'total_days',
        'used_days',
        'carried_over',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id', 'user_id')
            ->where('status', 'approved')
            ->where(function ($q) {
                $startOfYear = Carbon::create($this->year, 1, 1)->startOfDay()->timestamp;
                $endOfYear   = Carbon::create($this->year, 12, 31)->endOfDay()->timestamp;
                $q->whereBetween('start_date', [$startOfYear, $endOfYear]);
            });
    }
}
