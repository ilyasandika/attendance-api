<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            "users" => $this->collection->map(function ($user) {
                return [
                    "id" => $user->id,
                    "profileId" => $user->profile->id,
                    "employeeId" => $user->employee_id,
                    "employeeEmail" => $user->email,
                    "employeeName" => $user->profile->name,
                    "employeeRole" => $user->profile->role->name,
                    "employeeDepartment" => $user->profile->department->name,
                    "dateCreated" => strtotime($user->created_at),
                    "accountStatus" => $user->status,
                ];
            }),
            'meta' => [
                'currentPage' => $this->currentPage(),
                'lastPage' => $this->lastPage(),
                'total' => $this->total(),
            ],
            'links' => [
                "first" => $this->url(1),
                "last" => $this->url($this->lastPage()),
                "next" => $this->nextPageUrl(),
                "prev" => $this->previousPageUrl()
            ]
        ];
    }
}
