<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'totalUser' => $this->totalEmployee,
            'onTime' => $this->totalOnTime,
            'late' => $this->totalLate,
            'earlyLeave' => $this->totalEarlyLeave,
            'absent' => $this->totalAbsent,
            'dayOff' => $this->usersOff,
            'missingCheckOut' => $this->totalMissingCheckOut,
            'outsideLocationCheckIn' => $this->totalOutsideLocationCheckIn,
            'outsideLocationCheckOut' => $this->totalOutsideLocationCheckOut,
        ];
    }
}
