<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShiftCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'currentPage' => $this->currentPage(),
                'lastPage' => $this->lastPage(),
                'total' => $this->total(),
            ],
        ];
    }

    private function formatDay($collection, $day)
    {

        foreach ($collection as $col) {
            if ($col["name"] == $day) {
                return [
                    'in' => $col['check_in'] ?? 0,
                    'out' => $col['check_out'] ?? 0,
                    'breakStart' => $col['break_start'] ?? 0,
                    'breakEnd' => $col['break_end'] ?? 0,
                    'isOn' => $col['is_on'] ?? true,
                ];
            }
        }
    }
}
