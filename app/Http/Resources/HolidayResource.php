<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'holidayId' => $this->id,
            'name' => $this->name,
            'date' => $this->date,
        ];
    }
}
