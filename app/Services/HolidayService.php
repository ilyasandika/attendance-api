<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Resources\HolidayCollection;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;

class HolidayService
{
    public function getHolidayList($search = null)
    {
        $query = Holiday::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%");
        }

        $holidays = $query->orderBy('date', 'desc')->paginate(10);

        if (!$holidays || $holidays->isEmpty()) {
            return Helper::returnIfNotFound($holidays, "Holiday not found");
        }

        return Helper::returnSuccess(new HolidayCollection($holidays));
    }

    public function getHolidayById($id)
    {
        $holiday = Holiday::find($id);
        if (!$holiday) {
            return Helper::returnIfNotFound($holiday, "Holiday not found");
        }

        return Helper::returnSuccess(new HolidayResource($holiday));
    }

    public function createHoliday($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors(),
            ];
        }

        $holiday = Holiday::create([
            "name" => $data["name"],
            "date" => $data["date"],
        ]);

        return Helper::returnSuccess(new HolidayResource($holiday));
    }

    public function updateHolidayById($data, $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date,' . $id,
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors(),
            ];
        }

        $holiday = Holiday::find($id);
        if (!$holiday) {
            return Helper::returnIfNotFound($holiday, "Holiday not found");
        }


        $holiday->update([
            'name' => $data['name'],
            'date' => $data['date'],
        ]);

        return Helper::returnSuccess(new HolidayResource($holiday));
    }


    public function deleteHolidayById($id)
    {
        $holiday = Holiday::find($id);
        if (!$holiday) {
            return Helper::returnIfNotFound($holiday, "Holiday not found");
        }

        $holiday->delete();

        return Helper::returnSuccess(new HolidayResource($holiday));
    }

}
