<?php

namespace App\Services;

use App\Exceptions\FieldInUseException;
use App\Http\Resources\ShiftCollection;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use App\Models\ShiftDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShiftServices
{
    private array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function getShiftList(bool $isAll = false, $search = null)
    {
        if ($isAll) {
            $shifts = Shift::get()->map(function ($shift) {
                return [
                    "id" => $shift->id,
                    "name" => $shift->name,
                    "default" => $shift->default,
                ];
            });
        } else {
            $query = Shift::query();
            if ($search) $query->where("name", "like", "%{$search}%");

            $shifts = new ShiftCollection($query->with("shiftDay")->paginate(10));
        }

        return $shifts;
    }

    public function getShiftById(int $id)
    {
        $shift = Shift::with("shiftDay")->find($id);
        if (!$shift) {
            throw new notFoundHttpException(__('errorMessages.not_found'));
        }
        return new ShiftResource($shift);
    }

    public function createShift(array $data)
    {
        return DB::transaction(function () use ($data) {
            $shift = Shift::create([
                "name" => $data['name'],
                "description" => $data['description'],
                "default" => $data['default'],
            ]);
            foreach ($this->days as $day) {
                ShiftDay::create([
                    "shift_id" => $shift->id,
                    "name" => $day,
                    "check_in" => $data[$day]["in"],
                    "check_out" => $data[$day]["out"],
                    "break_start" => $data[$day]["breakStart"],
                    "break_end" => $data[$day]["breakEnd"],
                    "is_on" => $data[$day]["isOn"],
                ]);
            }
            return $data;
        });
    }
    public function updateShiftById(array $data, int $id)
    {

        $shift  = Shift::with("shiftDay")->find($id);

        if (!$shift) {
           throw new notFoundHttpException(__('errorMessages.not_found'));
        }


        return DB::transaction(function () use ($data, $shift) {
            $shift->name = $data['name'];
            $shift->description = $data['description'];
            $shift->default = $data['default'];
            $shift->save();

            foreach ($this->days as $day) {
                $shiftDay = ShiftDay::where('name', $day)->where('shift_id', $shift->id)->first();
                if ($shiftDay) {
                    $shiftDay->update([
                        'check_in' => $data[$day]["in"],
                        'check_out' => $data[$day]["out"],
                        'break_start' => $data[$day]["breakStart"],
                        'break_end' => $data[$day]["breakEnd"],
                        'is_on' => $data[$day]["isOn"]
                    ]);
                }
            }
            return $data;
        });
    }

    public function getUserShiftByUserId ($userid) {
        $shift = Shift::with("shiftDay")->whereHas('schedule', function ($query) use ($userid) {
            $query->where('user_id', $userid);
        });

        return new ShiftResource($shift->first());
    }

    public function deleteShiftById(int $id)
    {
        $shift = Shift::with("shiftDay")->find($id);
        if (!$shift) {
            throw new notFoundHttpException(__('errorMessages.not_found'));
        }
        if ($shift->schedule()->exists()) {
            throw new FieldInUseException(__('errorMessages.field_in_use'));
        }
        $shift->delete();

        return "";
    }

}
