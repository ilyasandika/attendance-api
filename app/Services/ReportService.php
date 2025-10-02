<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;

class ReportService
{
    public function getUserAttendanceReport(int $userId, $date = [])
    {
        $startDate = $date['startDate'] ?? Carbon::today()->subDays(30)->endOfDay()->timestamp;
        $endDate   = $date['endDate'] ?? Carbon::today()->endOfDay()->timestamp;

        Helper::findOrError(User::class, $userId);

        $leaveDates = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->get();

        $leaveDays = collect();

        foreach ($leaveDates as $leaveDate) {
            $sd = Carbon::createFromTimestamp($leaveDate->start_date);
            $ed = Carbon::createFromTimestamp($leaveDate->end_date);
            $period = $sd->toPeriod($ed);
            foreach ($period as $day) {
                $leaveDays->push($day->startOfDay()->timestamp);
            }
        }

        $attendances = Attendance::with('user.profile.role', 'user.profile.department')
            ->where('user_id', $userId)
            ->whereNotNull('check_in_status')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotIn('date', $leaveDays)
        ->get();

        $total = $attendances->count();
        $leave = $leaveDays->unique()->count();
        $absent = $attendances->where('check_in_status', 'absent')->count();
        $late = $attendances->where('check_in_status', 'late')->count();
        $earlyLeave = $attendances->where('check_out_status', 'early leave')->count();
        $onTime = $attendances->where('check_in_status', 'on time')->count();

        return [
            'total' => $total,
            'absent' => [
                'value' => $absent,
                'percentage' => $total ? round(($absent / $total) * 100, 2) : 0,
            ],
            'late' => [
                'value' => $late,
                'percentage' => $total ? round(($late / $total) * 100, 2) : 0,
            ],
            'earlyLeave' => [
                'value' => $earlyLeave,
                'percentage' => $total ? round(($earlyLeave / $total) * 100, 2) : 0,
            ],
            'onTime' => [
                'value' => $onTime,
                'percentage' => $total ? round(($onTime / $total) * 100, 2) : 0,
            ],
            'leave' => [
                'value' => $leave,
                'percentage' => $total ? round(($leave / $total) * 100, 2) : 0,
            ]
        ];
    }
}
