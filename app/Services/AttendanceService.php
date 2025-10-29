<?php

namespace App\Services;

use App\Exceptions\FieldInUseException;
use App\Exceptions\OutsideLocationException;
use App\Http\Resources\AttendanceCollection;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\AttendanceSummaryResource;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Helpers\Helper;

class AttendanceService
{
    public function getAttendanceList($search = null, int $rows = 10, string $date = "", ?int $userId = null, bool $all = false)
    {
        $query = Attendance::query()
            ->with(['user.profile.role', 'user.profile.department']);

        // === Filter by date ===
        if ($date) {
            $carbonDate = Carbon::parse($date);
            $startOfDay = $carbonDate->copy()->startOfDay();
            $endOfDay = $carbonDate->copy()->endOfDay();
            $query->whereBetween('date', [$startOfDay->timestamp, $endOfDay->timestamp]);
        }

        // === Filter by user ===
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // === Filter by search ===
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', fn ($q) =>
                    $q->where('name', 'like', "%{$search}%")
                    );
            });
        }

        // === Ordering ===
        $query->orderByDesc('date')
            ->orderByDesc('check_in_time');

        // === Return result ===
        if ($date && $userId && !$all) {
            // Single user's attendance for a specific day
            $attendance = $query->first();
            return $attendance
                ? new AttendanceResource($attendance)
                : null;
        }

        // Default: paginated list
        return new AttendanceCollection($query->paginate($rows));
    }

    public function getAttendanceById(int $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }
        return new AttendanceResource($attendance);
    }

    public function getAttendanceListByUserId(int $userId, $search = null, $rows = 10)
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

        $attendance = new AttendanceCollection($query->where("user_id", $userId)->orderBy('date', 'desc')->paginate($rows));

        if (!$attendance) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        return $attendance;
    }

    public function getAttendanceSummary($date = null) {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        $dayName = strtolower($date->format('l'));

        $startOfDay = $date->copy()->startOfDay()->timestamp;
        $endOfDay   = $date->copy()->endOfDay()->timestamp;

        $usersOff = User::whereHas('schedule.shift.shiftDay', function ($q) use ($dayName) {
            $q->where('name', $dayName)->where('is_on', 0);
        })->count();


        $totalEmployee = User::count();
        $totalOnTime = Attendance::whereBetween('date', [$startOfDay, $endOfDay])->where('check_in_status', 'on time')->count();
        $totalLate = Attendance::whereBetween('date', [$startOfDay, $endOfDay])->where('check_in_status', 'late')->count();
        $totalAbsent = Attendance::whereBetween('date', [$startOfDay, $endOfDay])->where('check_in_status', 'absent')->count();
        $totalEarlyLeave= Attendance::whereBetween('date', [$startOfDay, $endOfDay])->where('check_out_status', 'early leave')->count();
        $totalMissingCheckOut = Attendance::whereBetween('date', [$startOfDay, $endOfDay])->where('check_out_status', 'absent')->count();
        $totalOutsideLocationCheckIn = Attendance::whereBetween('date', [$startOfDay, $endOfDay])->where('check_in_outside_location', 1)->count();
        $totalOutsideLocationCheckOut =Attendance::whereBetween('date', [$startOfDay, $endOfDay])->where('check_out_outside_location', 1)->count();

        $attendanceSummary = [
            'totalEmployee' => $this->valuePercentage($totalEmployee, $totalEmployee),
            'totalOnTime'   => $this->valuePercentage($totalOnTime, $totalEmployee),
            'totalLate'     => $this->valuePercentage($totalLate, $totalEmployee),
            'totalAbsent'   => $this->valuePercentage($totalAbsent, $totalEmployee),
            'totalEarlyLeave' => $this->valuePercentage($totalEarlyLeave, $totalEmployee),
            'totalMissingCheckOut' => $this->valuePercentage($totalMissingCheckOut, $totalEmployee),
            'totalOutsideLocationCheckIn' =>$this->valuePercentage($totalOutsideLocationCheckIn, $totalEmployee),
            'totalOutsideLocationCheckOut' => $this->valuePercentage($totalOutsideLocationCheckOut, $totalEmployee),
            'usersOff' => $this->valuePercentage($usersOff, $totalEmployee)
        ];

        return new AttendanceSummaryResource((object) $attendanceSummary);
    }

    private function valuePercentage ($value, $total): array
    {
        return [
            "value" => $value,
            "percentage" => round($value / $total * 100, 2),
        ];
    }

    public function getAttendanceTimeLine($date = null, $option = "monthly") {
        $date = $date ? Carbon::parse($date) : Carbon::today();


        $yearsBack = 5;

        $years = [];
        $result = [];


        for ($i = 0; $i < $yearsBack; $i++) {
            $years[] = $date->copy()->subYears($i)->format('Y');
        }

        if ($option === "daily") {
            $dates = [];
            $daysBack = 7;

            for ($i = 0; $i < $daysBack; $i++) {
                $dates[] = $date->copy()->subDays($i)->format('Y-m-d');
            }

            $timeline = Attendance::select(
                DB::raw('DATE(FROM_UNIXTIME(check_in_time)) as date'),
                DB::raw('COUNT(*) as totalPresent')
            )
                ->where('date', '>=', $date->copy()->subDays($daysBack)->startOfDay()->timestamp)
                ->groupBy(DB::raw('DATE(FROM_UNIXTIME(check_in_time))'))
                ->orderBy('date')
                ->get()
                ->keyBy('date');


            foreach ($dates as $d) {
                $userOn = Attendance::whereBetween('date', [Carbon::parse($d)->copy()->startOfDay()->timestamp, Carbon::parse($d)->copy()->endOfDay()->timestamp])->count();

                $present = $timeline->has($d) ? $timeline[$d]->totalPresent : 0;

                $result[] = [
                    'date' => $d,
                    'totalPresent' => $userOn > 0 ? round($present / $userOn, 2) : 0,
                    'type' => 'daily'
                ];
            }
        }


        if ($option === "monthly") {
            $monthsBack = 12;
            $months = [];

            for ($i = 0; $i < $monthsBack; $i++) {
                $months[] = $date->copy()->subMonths($i)->format('Y-m');
            }

            $timelineByMonth = Attendance::select(
                DB::raw("DATE_FORMAT(FROM_UNIXTIME(check_in_time), '%Y-%m') as month"),
                DB::raw("COUNT(*) as totalPresent")
            )
                ->where('date', '>=', $date->copy()->subMonths($monthsBack)->startOfMonth()->timestamp)
                ->whereNotNull('check_in_time')
                ->groupBy(DB::raw("DATE_FORMAT(FROM_UNIXTIME(check_in_time), '%Y-%m')"))
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            foreach ($months as $m) {
                $startOfMonth = Carbon::parse($m)->startOfMonth()->timestamp;
                $endOfMonth   = Carbon::parse($m)->endOfMonth()->timestamp;

                $totalUsers = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])->count();

                $present = $timelineByMonth->has($m) ? $timelineByMonth[$m]->totalPresent : 0;

                $result[] = [
                    'date' => $m,
                    'totalPresent' => $totalUsers > 0 ? round($present / $totalUsers, 2) : 0,
                    'type' => 'monthly'
                ];
            }
        }

        if ($option === "yearly") {
            $yearsBack = 5;
            $years = [];

            for ($i = 0; $i < $yearsBack; $i++) {
                $years[] = $date->copy()->subYears($i)->format('Y');
            }

            // Ambil total hadir (present) per tahun
            $timelineByYear = Attendance::select(
                DB::raw("DATE_FORMAT(FROM_UNIXTIME(check_in_time), '%Y') as year"),
                DB::raw("COUNT(*) as totalPresent")
            )
                ->where('date', '>=', $date->copy()->subYears($yearsBack)->startOfYear()->timestamp)
                ->whereNotNull('check_in_time')
                ->groupBy(DB::raw("DATE_FORMAT(FROM_UNIXTIME(check_in_time), '%Y')"))
                ->orderBy('year')
                ->get()
                ->keyBy('year');

            foreach ($years as $y) {
                $startOfYear = Carbon::createFromFormat('Y', $y)->startOfYear()->timestamp;
                $endOfYear   = Carbon::createFromFormat('Y', $y)->endOfYear()->timestamp;

                $totalUsers = Attendance::whereBetween('date', [$startOfYear, $endOfYear])->count();

                $present = $timelineByYear->has($y) ? $timelineByYear[$y]->totalPresent : 0;

                $result[] = [
                    'date' => $y,
                    'totalPresent' => $totalUsers > 0 ? round($present / $totalUsers, 2) : 0,
                    'type' => 'yearly'
                ];
            }
        }



        return array_reverse($result);
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
        $breakStartSchedule = $user->schedule->shift->shiftDay->where('name', $day)->first()->break_start;
        $breakEndSchedule = $user->schedule->shift->shiftDay->where('name', $day)->first()->break_end;
        $today = now()->format('Y-m-d');
        $checkInScheduleTimestamp = strtotime("$today $checkInSchedule");
        $checkOutScheduleTimestamp = strtotime("$today $checkOutSchedule");
        $breakStartScheduleTimestamp = strtotime("$today $breakStartSchedule");
        $breakEndScheduleTimestamp = strtotime("$today $breakEndSchedule");
        $breakTime = ($breakEndScheduleTimestamp - $breakStartScheduleTimestamp);


        $attendance = Attendance::where('user_id', $userId)
            ->whereBetween('date', [(int) $startOfDay, (int) $endOfDay])
            ->first();

        $type = $attendance?->check_in_time === null ? 'in' : 'out';

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
            $attendance->start_time = $checkInSchedule;
            $attendance->end_time = $checkOutSchedule;
            $attendance->date = strtotime(now());
        }

        if ($type === 'in') {

            $attendance->check_in_time = strtotime(now());
            Log::info($attendance->check_in_time);
            $attendance->check_in_latitude = $data['latitude'];
            $attendance->check_in_longitude = $data['longitude'];
            $attendance->check_in_status = $attendance->check_in_time < $checkInScheduleTimestamp ? "on time" : "late";
            $attendance->check_in_photo = $filename;
            $attendance->check_in_outside_location = $outsideLocation;
            $attendance->check_in_address = $data['address'];
            $attendance->check_in_comment = $data['comment'] ?? null;
            if ($attendance->check_in_time > $checkInScheduleTimestamp) {
                $attendance->late_minutes = ($attendance->check_in_time - $checkInScheduleTimestamp) / 60;
            }

        } else {

            if ($attendance->check_out_photo) {
                Storage::disk('public')->delete($attendance->check_out_photo);
            }

            $attendance->check_out_time = strtotime(now());
            $attendance->check_out_latitude = $data['latitude'];
            $attendance->check_out_longitude = $data['longitude'];
            $attendance->check_out_status = $attendance->check_out_time > $checkOutScheduleTimestamp ? "on time" : "early leave";
            $attendance->check_out_photo = $filename;
            $attendance->check_out_outside_location = $outsideLocation;
            $attendance->check_out_address = $data['address'] ?? null;
            $attendance->check_out_comment = $data['comment'] ?? null;
            $attendance->duration =max(0, ($attendance->check_out_time - $attendance->check_in_time) / 60);
            if ($attendance->check_out_time < $checkOutScheduleTimestamp) {
                $attendance->early_leave_minutes = ($checkOutScheduleTimestamp - $attendance->check_out_time) / 60;
            }

            if ($attendance->check_out_time > $checkOutScheduleTimestamp) {
                $attendance->overtime_minutes = ($attendance->check_out_time - $checkOutScheduleTimestamp) / 60;
            }
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

    public function generateDailyBaseline()
    {
        $path = 'photos/default_auto_checkout.jpg';
        $startOfDay = Carbon::today()->startOfDay()->timestamp;
        $endOfDay = Carbon::today()->endOfDay()->timestamp;

        $users = User::with('schedule.shift.shiftDay', 'schedule.location')
            ->get();

        foreach ($users as $user) {
            if (!$user->schedule || Helper::isOff($user->id)) continue;

            $dayName = strtolower(now()->format('l'));
            $shiftDay = $user->schedule->shift->shiftDay->where('name', $dayName)->first();

            if (!$shiftDay || !$shiftDay->is_on) continue;

            $attendance = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfDay, $endOfDay])
                ->first();

            if (!$attendance) {
                // buat baseline record status absent
                Attendance::create([
                    'user_id'        => $user->id,
                    'date'           => $startOfDay,
                    'check_in_time'  => null,
                    'check_in_status'  => null,
                    'check_out_time' => null,
                    'check_out_status' => null,
                    'photo'          => $path,
                ]);
            }
        }

        return true;
    }

    private function updateForceCheckout($attendance, $now, $path)
    {
        $attendance->check_out_time = $now;
        $attendance->check_out_status = "absent";
        $attendance->check_out_outside_location = true;
        $attendance->auto_checkout = true;
        $attendance->check_out_photo = $path;

        if (is_null($attendance->check_in_time)) {
            $attendance->check_in_status = "absent";
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
            'check_out_status' => "absent",
            'check_in_status' => "absent",
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
