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
            'totalUser' => $this->valuePercentage($this->totalEmployee),
            'onTime' => $this->valuePercentage($this->totalOnTime),
            'late' => $this->valuePercentage($this->totalLate),
            'earlyLeave' => $this->valuePercentage($this->totalEarlyLeave),
            'absent' => $this->valuePercentage($this->totalAbsent),
            'dayOff' => $this->valuePercentage($this->usersOff),
            'missingCheckOut' => $this->valuePercentage($this->totalMissingCheckOut),
            'outsideLocationCheckIn' => $this->valuePercentage($this->totalOutsideLocationCheckIn),
            'outsideLocationCheckOut' => $this->valuePercentage($this->totalOutsideLocationCheckOut),
        ];
    }

    private function valuePercentage ($val): array
    {
        return [
            "value" => $val['value'],
            "percentage" => $val['percentage'],
        ];
    }
}
