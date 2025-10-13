<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportService
{
    public function getUserAttendanceReport(int $userId,  $startDate = null,  $endDate = null)
    {
        $date = [];
        if ($startDate && $endDate) {
            $date = [
                'startDate' => (int) $startDate,
                'endDate' => (int) $endDate,
            ];
        } else {
            $date = Helper::getDateRangeOrDefaultFromString($startDate, $endDate);
        }

        Helper::findOrError(User::class, $userId);
        $leaveDates = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function ($q) use ($date) {
                $q->whereBetween('start_date', [$date['startDate'], $date['endDate']])
                    ->orWhereBetween('end_date', [$date['startDate'], $date['endDate']])
                    ->orWhere(function ($q2) use ($date) {
                        $q2->where('start_date', '<=', $date['startDate'])
                            ->where('end_date', '>=', $date['endDate']);
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
            ->whereBetween('date', [$date['startDate'], $date['endDate']])
//            ->whereNotNull('check_in_status')
            ->whereNotIn('date', $leaveDays)
        ->get();



        $total = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$date['startDate'], $date['endDate']])->count();

        $leave = $leaveDays->unique()->count();
        $absent = $attendances->where('check_in_status', 'absent')->count() - $leave;
        $late = $attendances->where('check_in_status', 'late')->count();
        $earlyLeave = $attendances->where('check_out_status', 'early leave')->count();
        $onTime = $attendances->where('check_in_status', 'on time')->count();
        $checkInOutsideLocation = $attendances->where('check_in_outside_location', 1)->count() - $leave;
        $checkOutOutsideLocation = $attendances->where('check_out_outside_location', 1)->count() - $leave;

        return  [
            'total' => $total,
            'absent' => $this->valuePercentage($absent, $total),
            'late' => $this->valuePercentage($late, $total),
            'earlyLeave' => $this->valuePercentage($earlyLeave, $total),
            'onTime' => $this->valuePercentage($onTime, $total),
            'leave' => $this->valuePercentage($leave, $total),
            'checkInOutsideLocation' => $this->valuePercentage($checkInOutsideLocation, $total),
            'checkOutOutsideLocation' => $this->valuePercentage($checkOutOutsideLocation, $total),
        ];

    }

    private function valuePercentage ($value, $total): array
    {
        return [
            "value" => $value,
            "percentage" => $total === 0 ? 0 : round($value / $total * 100, 2),
        ];
    }

    public function getUserAttendanceReportByYear(int $userId, int $year)
    {

        $monthlyReports = collect(range(1, 12))->mapWithKeys(function ($month) use ($userId, $year) {
            $start = Carbon::create($year, $month, 1)->startOfMonth()->timestamp;
            $end = Carbon::create($year, $month, 1)->endOfMonth()->timestamp;

            $report = $this->getUserAttendanceReport($userId, $start, $end);

            return [
                $month => [
                    'name' => Carbon::create()->month($month)->translatedFormat('F'),
                    'data' => $report,
                ],
            ];


        });

        return $monthlyReports;
    }

    public function getAvailableReportYears($userId = null)
    {
        $years = Attendance::when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->selectRaw('YEAR(FROM_UNIXTIME(date)) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return $years;
    }



}
