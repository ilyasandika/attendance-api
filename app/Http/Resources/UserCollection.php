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
                    "email" => $user->email,
                    "name" => $user->profile->name,
                    "role" => $user->profile->role->name,
                    "department" => $user->profile->department->name,
                    "dateCreated" => strtotime($user->created_at),
                    "status" => $user->status,
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
