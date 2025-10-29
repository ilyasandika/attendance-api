<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Resources\HolidayCollection;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;

class HolidayService
{
    public function getHolidayList($search = null, int $rows= 10)
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

        return new HolidayCollection($holidays);
    }

    public function getHolidayById($id)
    {
        $holiday = Helper::findOrError(Holiday::class, $id);


        return new HolidayResource($holiday);
    }

    public function createHoliday($data)
    {

        $holiday = Holiday::create([
            "name" => $data["name"],
            "date" => $data["date"],
        ]);

        return new HolidayResource($holiday);
    }

    public function updateHolidayById($data, $id)
    {
        $holiday = Holiday::find($id);
        if (!$holiday) {
            return Helper::returnIfNotFound($holiday, "Holiday not found");
        }


        $holiday->update([
            'name' => $data['name'],
            'date' => $data['date'],
        ]);

        return new HolidayResource($holiday);
    }


    public function deleteHolidayById($id)
    {
        $holiday = Helper::findOrError(Holiday::class, $id);
        $holiday->delete();
    }

}
