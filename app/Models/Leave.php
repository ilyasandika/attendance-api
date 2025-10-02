<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'comment',
        'approved_at',
    ];


    public function getAttachmentUrlAttribute()
    {
        return $this->attachment
            ? url("storage/{$this->attachment}")
            : null;
    }

    public function getYearAttribute()
    {
        return $this->start_date
            ? Carbon::createFromTimestamp($this->start_date)->year
            : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
