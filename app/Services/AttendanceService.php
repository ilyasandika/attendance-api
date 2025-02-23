<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttendanceService
{
    public function getAttendanceList()
    {
        $attendance = Attendance::paginate(10);
        return $attendance ? Helper::returnSuccess($attendance) : Helper::returnIfNotFound($attendance, "Attendance not found");
    }

    public function createAttendance(Request $request, int $userId)
    {

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return [
                "status" => false,
                "errors" => $validator->errors()
            ];
        }

        $data = $request->all();
        $user = User::with('schedule.shift.shiftDay', 'schedule.location')->find($userId);

        $day = strtolower(now()->format('l'));
        $checkInSchedule = $user->schedule->shift->shiftDay->where('name', $day)->first()->check_in;
        $today = now()->format('Y-m-d');
        $checkInScheduleTimestamp = strtotime("$today $checkInSchedule");

        $startOfDay = strtotime($today . ' 00:00:00');
        $endOfDay = strtotime($today . ' 23:59:59');

        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startOfDay, $endOfDay])
            ->first();

        if ($existingAttendance) {
            return $this->updateAttendance($request, $userId);
        }

        $file = $request->file('photo');
        $filename = auth()->user()->employee_id . "_" . strtotime(now()) . "_in." . $file->getClientOriginalExtension();
        $path = $file->storeAs('photos', $filename, 'public');

        $workLatitude = $user->schedule->location->latitude;
        $worklongitude = $user->schedule->location->longitude;
        $locationRadius = $user->schedule->location->radius;

        $attendance = new Attendance();

        $attendance->user_id = $userId;
        $attendance->date = strtotime(now());
        $attendance->check_in_time = strtotime(now());
        $attendance->latitude_check_in = $data['latitude'];
        $attendance->longitude_check_in = $data['longitude'];
        $attendance->schedule_id = $user->schedule->id;
        $attendance->check_in_status = $attendance->check_in_time < $checkInScheduleTimestamp ? "On Time" : "Late";
        $attendance->checkin_outside_location = !Helper::isWithinRadius($attendance->latitude_check_in, $attendance->longitude_check_in, $workLatitude, $worklongitude, $locationRadius);
        $attendance->photo_check_in = $path;
        $attendance->save();

        return Helper::returnSuccess($attendance);
    }

    public function updateAttendance(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return [
                "status" => false,
                "errors" => $validator->errors()
            ];
        }

        $data = $request->all();
        $today = now()->toDateString();

        $startOfDay = strtotime($today . ' 00:00:00');
        $endOfDay = strtotime($today . ' 23:59:59');

        $attendance = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startOfDay, $endOfDay])
            ->first();

        $user = User::with('schedule.shift.shiftDay')->find($userId);
        $day = strtolower(now()->format('l'));
        $checkOutSchedule = $user->schedule->shift->shiftDay->where('name', $day)->first()->check_out;
        $checkOutScheduleTimestamp = strtotime("$today $checkOutSchedule");

        // Hapus foto check-out sebelumnya jika ada


        $workLatitude = $user->schedule->location->latitude;
        $worklongitude = $user->schedule->location->longitude;
        $locationRadius = $user->schedule->location->radius;

        $attendance->check_out_time = strtotime(now());

        if ($attendance->photo_check_out) {
            Storage::disk('public')->delete($attendance->photo_check_out);
        }


        $file = $request->file('photo');
        $filename = auth()->user()->employee_id . "_" . $attendance->check_out_time . "_out." . $file->getClientOriginalExtension();
        $path = $file->storeAs('photos', $filename, 'public');

        $attendance->latitude_check_out = $data['latitude'];
        $attendance->longitude_check_out = $data['longitude'];
        $attendance->photo_check_out = $path;
        $attendance->check_out_status = $attendance->check_out_time > $checkOutScheduleTimestamp ? "On Time" : "Early Leave";
        $attendance->checkout_outside_location = !Helper::isWithinRadius($attendance->latitude_check_out, $attendance->longitude_check_out, $workLatitude, $worklongitude, $locationRadius);

        $attendance->save();

        return Helper::returnSuccess([
            "message" => "Check-out successful",
            "data" => $attendance
        ]);
    }
}
