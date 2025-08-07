<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Resources\AttendanceCollection;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttendanceService
{
    public function getAttendanceList($search = null)
    {
        $query = Attendance::query();

        $query->with('user.profile.role', 'user.profile.department');
        if ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $attendance = new AttendanceCollection($query->orderBy('date', 'desc')->paginate(10));

        if (!$attendance) {
            return Helper::returnIfNotFound($attendance, "Attendance not found");
        }

        return Helper::returnSuccess($attendance);
    }

    public function getAttendanceById(int $id)
    {
        $attendance = Attendance::find($id);
        return $attendance ? Helper::returnSuccess(new AttendanceResource($attendance)) : Helper::returnIfNotFound($attendance, "Attendance not found");
    }

    public function getAttendanceListByUserId(int $userId, $search = null)
    {
        $query = Attendance::query();

        $query->with('user.profile.role', 'user.profile.department');
        if ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $attendance = new AttendanceCollection($query->where("user_id", $userId)->orderBy('date', 'desc')->paginate(10));

        if (!$attendance) {
            return Helper::returnIfNotFound($attendance, "Attendance not found");
        }

        return Helper::returnSuccess($attendance);
    }



    public function createAttendance(Request $request, int $userId)
    {
        if (Helper::isOff($userId)) {
            return Helper::returnIfNotFound(null, "User is off");
        }

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'comment' => 'nullable|string',
            'address' => 'nullable|string',
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
        $workLongitude = $user->schedule->location->longitude;
        $locationRadius = $user->schedule->location->radius;

        // ATTENDANCE
        $attendance = new Attendance();
        $attendance->user_id = $userId;
        $attendance->schedule_id = $user->schedule->id;
        $attendance->date = strtotime(now());
        $attendance->check_in_time = strtotime(now());
        $attendance->check_in_latitude = $data['latitude'];
        $attendance->check_in_longitude = $data['longitude'];
        $attendance->check_in_status = $attendance->check_in_time < $checkInScheduleTimestamp ? "On Time" : "Late";
        $attendance->check_in_outside_location = !Helper::isWithinRadius($attendance->check_in_latitude, $attendance->check_in_longitude, $workLatitude, $workLongitude, $locationRadius);
        $attendance->check_in_photo = $path;
        $attendance->check_in_address = $data['address'];
        $attendance->check_in_comment = $data['comment'] ?? null;
        $attendance->save();

        return Helper::returnSuccess($attendance);
    }

    public function updateAttendance(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'comment' => 'nullable|string',
            'address' => 'nullable|string'
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

        $workLatitude = $user->schedule->location->latitude;
        $workLongitude = $user->schedule->location->longitude;
        $locationRadius = $user->schedule->location->radius;

        $attendance->check_out_time = strtotime(now());

        // Hapus foto check-out sebelumnya jika ada
        if ($attendance->check_out_photo) {
            Storage::disk('public')->delete($attendance->check_out_photo);
        }

        $file = $request->file('photo');
        $filename = auth()->user()->employee_id . "_" . $attendance->check_out_time . "_out." . $file->getClientOriginalExtension();
        $path = $file->storeAs('photos', $filename, 'public');

        $attendance->check_out_latitude = $data['latitude'];
        $attendance->check_out_longitude = $data['longitude'];
        $attendance->check_out_photo = $path;
        $attendance->check_out_status = $attendance->check_out_time > $checkOutScheduleTimestamp ? "On Time" : "Early Leave";
        $attendance->check_out_outside_location = !Helper::isWithinRadius($attendance->check_out_latitude, $attendance->check_out_longitude, $workLatitude, $workLongitude, $locationRadius);
        $attendance->check_out_address = $data['address'] ?? null;
        $attendance->check_out_comment = $data['comment'] ?? null;
        $attendance->save();

        return Helper::returnSuccess([
            "message" => "Check-out successful",
            "data" => $attendance
        ]);
    }


    public function forceCheckoutAll()
    {
        $path = 'photos/default_auto_checkout.jpg';
        $today = now()->toDateString();
        $startOfDay = strtotime($today . ' 00:00:00');
        $endOfDay = strtotime($today . ' 23:59:59');

        $users = User::with('schedule.shift.shiftDay', 'schedule.location')->get();

        foreach ($users as $user) {
            if (!$user->schedule) continue;
            if (Helper::isOff($user->id)) continue;

            $dayName = strtolower(now()->format('l'));
            $shiftDay = $user->schedule->shift->shiftDay->where('name', $dayName)->first();

            if (!$shiftDay) continue; // jika tidak ada shift hari ini

            $checkOutTime = $shiftDay->check_out;
            $checkOutTimestamp = strtotime("$today $checkOutTime");

            $attendance = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfDay, $endOfDay])
                ->first();

            $location = $user->schedule->location;
            $now = strtotime(now());

            if ($attendance) {
                if (is_null($attendance->check_out_time)) {
                    $attendance->check_out_time = $now;
                    $attendance->check_out_status = "Absent";
                    $attendance->check_out_outside_location = true;
                    $attendance->auto_checkout = true;
                    $attendance->check_out_photo = $path;

                    if (is_null($attendance->check_in_time)) {
                        $attendance->check_in_status = "Absent";
                        $attendance->check_in_photo = $path;
                        $attendance->check_in_outside_location = true;
                    }

                    $attendance->save();
                }
            } else {
                // Belum ada data sama sekali
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $now,
                    'check_in_time' => null,
                    'check_out_time' => $now,
                    'check_out_status' => "Absent",
                    'check_in_status' => "Absent",
                    'check_in_latitude' => null,
                    'check_in_longitude' => null,
                    'check_out_latitude' => null,
                    'check_out_longitude' => null,
                    'check_in_outside_location' => true,
                    'check_out_outside_location' => true,
                    'schedule_id' => $user->schedule->id,
                    'check_in_photo' => $path,
                    'check_out_photo' => $path,
                    'auto_checkout' => true,
                ]);
            }
        }

        return Helper::returnSuccess("Force check-out completed for all users.");
    }
}
