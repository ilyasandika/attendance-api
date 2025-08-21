<?php

namespace App\Services;

use App\Exceptions\FieldInUseException;
use App\Exceptions\OutsideLocationException;
use App\Http\Resources\AttendanceCollection;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Helpers\Helper;

class AttendanceService
{
    public function getAttendanceList($search = null, $date = null, int $userId = null, bool $all = false)
    {
        $query = Attendance::query();


        $query->with('user.profile.role', 'user.profile.department');

        if ($date) {
            $timestamp = (int) $date;
            $date = date('Y-m-d', $timestamp);
            $startOfDay = strtotime($date . ' 00:00:00');
            $endOfDay = strtotime($date . ' 23:59:59');

            if ($all && !$userId) {
                $query->whereBetween('date', [$startOfDay, $endOfDay]);
                return new AttendanceCollection($query->paginate(10));
            }

            if (!$all && $userId) {

                $query->where('user_id', $userId)->whereBetween('date', [$startOfDay, $endOfDay]);

                $attendance = $query->first();
                if (!$attendance) {
                    return $attendance;
                }
                return new AttendanceResource($query->first());
            }

        }

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
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        return $attendance;
    }

    public function getAttendanceById(int $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }
        return new AttendanceResource($attendance);
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
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        return $attendance;
    }


    public function handleAttendance(array $data, $file,  int $userId)
    {
        if (Helper::isOff($userId)) {
            throw new NotFoundHttpException(__('errorMessages.user_is_off'));
        }


        $user = $this->getUserWithSchedule($userId);
        ['start' => $startOfDay, 'end' => $endOfDay] = $this->getStartEndOfDay();

        $day = strtolower(now()->format('l'));
        $checkInSchedule = $user->schedule->shift->shiftDay->where('name', $day)->first()->check_in;
        $checkOutSchedule = $user->schedule->shift->shiftDay->where('name', $day)->first()->check_out;
        $today = now()->format('Y-m-d');
        $checkInScheduleTimestamp = strtotime("$today $checkInSchedule");
        $checkOutScheduleTimestamp = strtotime("$today $checkOutSchedule");

        $attendance = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startOfDay, $endOfDay])
            ->first();

        $type = $attendance ? 'out' : 'in';

        $filename = $this->storeAttendancePhoto($file, $user->employee_id, $type);

        $outsideLocation = $this->isOutsideLocation(
            $data['latitude'],
            $data['longitude'],
            $user->schedule->location->latitude,
            $user->schedule->location->longitude,
            $user->schedule->location->radius
        );

        if (!$user->schedule->shift->allow_outside_location && $outsideLocation) {
            throw new OutsideLocationException(__('errorMessages.not_allowed_outside_location'));
        }

        if (!$attendance) {
            $attendance = new Attendance();
            $attendance->user_id = $userId;
            $attendance->schedule_id = $user->schedule->id;
            $attendance->date = strtotime(now());
        }

        if ($type === 'in') {

            $attendance->check_in_time = strtotime(now());
            $attendance->check_in_latitude = $data['latitude'];
            $attendance->check_in_longitude = $data['longitude'];
            $attendance->check_in_status = $attendance->check_in_time < $checkInScheduleTimestamp ? "On Time" : "Late";
            $attendance->check_in_photo = $filename;
            $attendance->check_in_outside_location = $outsideLocation;
            $attendance->check_in_address = $data['address'];
            $attendance->check_in_comment = $data['comment'] ?? null;
        } else {

            if ($attendance->check_out_photo) {
                Storage::disk('public')->delete($attendance->check_out_photo);
            }

            $attendance->check_out_time = strtotime(now());
            $attendance->check_out_latitude = $data['latitude'];
            $attendance->check_out_longitude = $data['longitude'];
            $attendance->check_out_status = $attendance->check_out_time > $checkOutScheduleTimestamp ? "On Time" : "Early Leave";
            $attendance->check_out_photo = $filename;
            $attendance->check_out_outside_location = $outsideLocation;
            $attendance->check_out_address = $data['address'] ?? null;
            $attendance->check_out_comment = $data['comment'] ?? null;
        }

        if (!$attendance->save()) {
            throw new \Exception($type === 'in' ? __('errorMessages.create_failed') : __('errorMessages.update_failed'));
        }

        return $attendance;
    }

    private function getUserWithSchedule(int $userId)
    {
        return User::with('schedule.shift.shiftDay', 'schedule.location')->findOrFail($userId);
    }

    private function getStartEndOfDay(): array
    {
        $today = now()->toDateString();
        return [
            'start' => strtotime("$today 00:00:00"),
            'end'   => strtotime("$today 23:59:59"),
        ];
    }

    private function storeAttendancePhoto($file, string $employeeId, string $type): string
    {
        $filename = $employeeId . "_" . strtotime(now()) . "_{$type}." . $file->getClientOriginalExtension();
        return $file->storeAs('photos', $filename, 'public');
    }

    private function isOutsideLocation($checkLat, $checkLong, $workLat, $workLong, $radius): bool
    {
        return !Helper::isWithinRadius($checkLat, $checkLong, $workLat, $workLong, $radius);
    }



    public function forceCheckoutAll()
    {
        $path = 'photos/default_auto_checkout.jpg';
        $today = now()->toDateString();
        $startOfDay = strtotime($today . ' 00:00:00');
        $endOfDay = strtotime($today . ' 23:59:59');

        $users = User::with('schedule.shift.shiftDay', 'schedule.location')->get();
        $now = strtotime(now());

        foreach ($users as $user) {
            if (!$user->schedule || Helper::isOff($user->id)) continue;

            $dayName = strtolower(now()->format('l'));
            $shiftDay = $user->schedule->shift->shiftDay->where('name', $dayName)->first();

            if (!$shiftDay) continue;

            $attendance = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfDay, $endOfDay])
                ->first();

            if ($attendance) {
                if (is_null($attendance->check_out_time)) {
                    $this->updateForceCheckout($attendance, $now, $path);
                }
            } else {
                $this->createForceCheckout($user, $now, $path);
            }
        }

        return true;
    }

    private function updateForceCheckout($attendance, $now, $path)
    {
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

    private function createForceCheckout($user, $now, $path)
    {
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
