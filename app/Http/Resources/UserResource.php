<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "employeeId" => $this->employee_id,
            "email" => $this->email,
            "name" => $this->profile->name,
            "gender" => $this->profile->gender,
            'profileId' => $this->profile->id,
            "birthDate" => $this->profile->birth_date,
            "phoneNumber" => $this->profile->phone_number,
            "role" => $this->profile->role->name,
            "roleId" => $this->profile->role->id,
            "department" => $this->profile->department->name,
            "departmentId" => $this->profile->department->id,
            "shift" => $this->schedule->shift->name,
            "shiftId" => $this->schedule->shift->id,
            "location" => $this->schedule->location->name,
            "locationId" => $this->schedule->location->id,
            "whatsapp" => $this->profile->whatsapp,
            "linkedin" => $this->profile->linkedin,
            "telegram" => $this->profile->telegram,
            "biography" => $this->profile->biography,
            "status" => $this->status,
            "photo" => $this->profile->profile_picture_path,
            "dateCreated" => strtotime($this->created_at),
        ];
    }
}
