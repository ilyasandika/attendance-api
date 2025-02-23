<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleService
{
    public function getScheduleList()
    {
        $schedule = Schedule::with('shift', 'location', 'user.profile', 'user.profile.department', 'user.profile.role')->get()->map(function ($schedule) {
            return [
                "schedulesId" => $schedule->id,
                "employeeId" => $schedule->user->employee_id,
                "employeeName" => $schedule->user->profile->name,
                "employeeRole" => $schedule->user->profile->role->name,
                "employeeDepartment" => $schedule->user->profile->department->name,
                "employeeShift" => $schedule->shift->name,
                "employeeWorkLocation" => $schedule->location->name
            ];
        });

        return ($schedule) ?  Helper::returnSuccess($schedule) : Helper::returnIfNotFound($schedule, "schedule not found");
    }

    public function updateScheduleById(array $data, int $id)
    {

        $data["id"] = $id;
        $validator = Validator::make($data, [
            "id" => 'required|integer',
            'employeeShiftId' => 'required|integer',
            'employeeLocationId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors()
            ];
        }

        $schedule = Schedule::find($id);

        if (!$schedule) return Helper::returnIfNotFound($schedule, "Schedule not found");

        $schedule->shift_id = $data['employeeShiftId'];
        $schedule->location_id = $data['employeeLocationId'];
        $schedule->save();

        return Helper::returnSuccess($schedule);
    }


    public function createLocation(array $data)
    {

        $validator = Validator::make($data, [
            "locationName" => "required|string",
            "latitude" => "required|decimal:7",
            "longtitude" => "required|decimal:7",
            "radius" => "required|integer",
            "address" => "required|string",
            "description" => "required|string",
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors()
            ];
        }

        $location = Location::create([
            "name" => $data['locationName'],
            "latitude" => $data['latitude'],
            "longtitude" => $data['longtitude'],
            "radius" => $data['radius'],
            "address" => $data['address'],
            "description" => $data['description']
        ]);

        if (!$location) {
            return Helper::returnIfNotFound($location, "create location failed");
        }

        return Helper::returnSuccess($location);
    }

    public function getLocationList()
    {
        $location = Location::get();
        return ($location) ?  Helper::returnSuccess($location) : Helper::returnIfNotFound($location, "location not found");
    }

    public function getLocationById($id)
    {
        $location = location::find($id);
        return ($location) ?  Helper::returnSuccess($location) : Helper::returnIfNotFound($location, "location not found");
    }

    public function updateLocationById(array $data, int $id)
    {

        $validator = Validator::make($data, [
            "locationName" => "required|string",
            "description" => "required|string",
            "latitude" => "required|decimal:7",
            "longtitude" => "required|decimal:7",
            "radius" => "required|integer",
            "address" => "required|string",
        ]);

        if ($validator->fails()) {
            return [
                "status" => false,
                "errors" => $validator->errors()
            ];
        }

        $location = location::find($id);
        if ($location) {
            $location->name = $data["locationName"];
            $location->description = $data["description"];
            $location->latitude = $data["latitude"];
            $location->longtitude = $data["longtitude"];
            $location->radius = $data["radius"];
            $location->address = $data["address"];
            $location->save();
        } else {
            return Helper::returnIfNotFound($location, "location not found");
        }

        return Helper::returnSuccess($location);
    }

    public function deleteLocationById(int $id)
    {
        $location = Location::find($id);
        if (!$location) return Helper::returnIfNotFound($location, "location not found");

        $location->delete();

        return Helper::returnSuccess($location);
    }


    public function createShift(array $data)
    {

        $validator = Validator::make($data, [
            "shiftName" => "required|string",
            "description" => "required|string",
            "monday" => "required|array",
            "tuesday" => "required|array",
            "wednesday" => "required|array",
            "thursday" => "required|array",
            "friday" => "required|array",
            "saturday" => "required|array",
            "sunday" => "required|array",
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors()
            ];
        }


        DB::beginTransaction();
        try {

            $shift = Shift::create([
                "name" => $data['shiftName'],
                "description" => $data['description'],
            ]);

            ShiftDay::create([
                "shift_id" => $shift->id,
                "name" => "monday",
                "check_in" => $data['monday']["in"],
                "check_out" => $data['monday']["out"],
                "break_start" => $data['monday']["breakStart"],
                "break_end" => $data['monday']["breakEnd"],
                "is_off" => $data['monday']["isOff"],
            ]);

            ShiftDay::create([
                "shift_id" => $shift->id,
                "name" => "tuesday",
                "check_in" => $data['tuesday']["in"],
                "check_out" => $data['tuesday']["out"],
                "break_start" => $data['tuesday']["breakStart"],
                "break_end" => $data['tuesday']["breakEnd"],
                "is_off" => $data['tuesday']["isOff"],
            ]);

            ShiftDay::create([
                "shift_id" => $shift->id,
                "name" => "wednesday",
                "check_in" => $data['wednesday']["in"],
                "check_out" => $data['wednesday']["out"],
                "break_start" => $data['wednesday']["breakStart"],
                "break_end" => $data['wednesday']["breakEnd"],
                "is_off" => $data['wednesday']["isOff"],
            ]);

            ShiftDay::create([
                "shift_id" => $shift->id,
                "name" => "thursday",
                "check_in" => $data['thursday']["in"],
                "check_out" => $data['thursday']["out"],
                "break_start" => $data['thursday']["breakStart"],
                "break_end" => $data['thursday']["breakEnd"],
                "is_off" => $data['thursday']["isOff"],
            ]);

            ShiftDay::create([
                "shift_id" => $shift->id,
                "name" => "friday",
                "check_in" => $data['friday']["in"],
                "check_out" => $data['friday']["out"],
                "break_start" => $data['friday']["breakStart"],
                "break_end" => $data['friday']["breakEnd"],
                "is_off" => $data['friday']["isOff"],
            ]);

            ShiftDay::create([
                "shift_id" => $shift->id,
                "name" => "saturday",
                "check_in" => $data['saturday']["in"],
                "check_out" => $data['saturday']["out"],
                "break_start" => $data['saturday']["breakStart"],
                "break_end" => $data['saturday']["breakEnd"],
                "is_off" => $data['saturday']["isOff"],
            ]);

            ShiftDay::create([
                "shift_id" => $shift->id,
                "name" => "sunday",
                "check_in" => $data['sunday']["in"],
                "check_out" => $data['sunday']["out"],
                "break_start" => $data['sunday']["breakStart"],
                "break_end" => $data['sunday']["breakEnd"],
                "is_off" => $data['sunday']["isOff"],
            ]);

            DB::commit();

            return Helper::returnSuccess($data);
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'errors' => ["message" => $e->getMessage()]
            ];
        }


        if (!$location) {
            return Helper::returnIfNotFound($location, "create location failed");
        }

        return Helper::returnSuccess($location);
    }

    public function getShiftList()
    {
        $shift = Shift::with("shiftDay")->get();
        return ($shift) ?  Helper::returnSuccess($shift) : Helper::returnIfNotFound($shift, "shift not found");
    }

    public function getShiftById(int $id)
    {
        $shift = Shift::with("shiftDay")->find($id);
        return ($shift) ?  Helper::returnSuccess($shift) : Helper::returnIfNotFound($shift, "shift not found");
    }



    public function updateShiftById(array $data, int $id)
    {

        $validator = Validator::make($data, [
            "shiftName" => "required|string",
            "description" => "required|string",
            "monday" => "required|array",
            "tuesday" => "required|array",
            "wednesday" => "required|array",
            "thursday" => "required|array",
            "friday" => "required|array",
            "saturday" => "required|array",
            "sunday" => "required|array",
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors()
            ];
        }

        $shift = Shift::with("shiftDay")->find($id);


        if (!$shift) {
            return Helper::returnIfNotFound($shift, "shift not found");
        }

        DB::beginTransaction();
        try {

            $shift->name = $data['shiftName'];
            $shift->description = $data['description'];
            $shift->save();

            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($days as $day) {
                $shiftDay = ShiftDay::where('name', $day)->where('shift_id', $id)->first();
                if ($shiftDay) {
                    $shiftDay->update([
                        'check_in' => $data[$day]["in"],
                        'check_out' => $data[$day]["out"],
                        'break_start' => $data[$day]["breakStart"],
                        'break_end' => $data[$day]["breakEnd"],
                        'is_off' => $data[$day]["isOff"]
                    ]);
                }
            }


            DB::commit();

            return Helper::returnSuccess($data);
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'errors' => ["message" => $e->getMessage()]
            ];
        }


        if (!$location) {
            return Helper::returnIfNotFound($location, "create location failed");
        }

        return Helper::returnSuccess($location);
    }

    public function deleteShiftById(int $id)
    {
        $shift = Shift::with("shiftDay")->find($id);
        $shift->delete();
        return ($shift) ?  Helper::returnSuccess($shift) : Helper::returnIfNotFound($shift, "shift not found");
    }
}
