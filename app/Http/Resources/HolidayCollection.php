<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HolidayCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($holiday) {
                return [
                    'holidayId' => $holiday->id,
                    'name' => $holiday->name,
                    'date' => $holiday->date,
                ];
            }),
            'meta' => [
                'currentPage' => $this->currentPage(),
                'lastPage' => $this->lastPage(),
                'total' => $this->total(),
            ],
        ];
    }
}
