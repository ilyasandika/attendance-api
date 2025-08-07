<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DepartmentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($department) {
                return [
                    'departmentId'   => $department->id,
                    'name'           => $department->name,
                    'description'    => $department->description,
                    'default'    => $department->default,
                    'isUsed' => $department->profiles()->exists(),
                ];
            }),
            'meta' => [
                'currentPage' => $this->currentPage(),
                'lastPage'    => $this->lastPage(),
                'total'       => $this->total(),
            ],
        ];
    }
}
