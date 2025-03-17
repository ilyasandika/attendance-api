<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LocationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "data" => $this->collection->map(function ($location) {
                return [
                    "id" => $location->id,
                    "name" => $location->name,
                    "description" => $location->description,
                    "address" => $location->address,
                    "latitude" => $location->latitude,
                    "longitude" => $location->longitude,
                    "radius" => $location->radius,
                    "createdAt" => $location->created_at,
                    "updatedAt" => $location->updated_at
                ];
            }),
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'total' => $this->total(),
            ],
        ];
    }
}
