<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\Attendance;
use App\Models\User;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SummaryAttendanceExportSheet implements FromCollection, WithTitle, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected ?int $userId;
    protected ?string $startDate;
    protected ?string $endDate;
    protected ?string $month;
    public function __construct(?int $userId = null, ?string $startDate = null, ?string $endDate = null)
    {
       $date = Helper::getDateRangeOrDefaultFromString($startDate, $endDate);
       $this->userId = $userId;
       $this->startDate = $date['startDate'];
       $this->endDate = $date['endDate'];
       $this->month = $date['month'];
    }

    public function collection(): Collection
    {
        if ($this->userId) {
            $user = Helper::findOrError(User::with(['profile.department', 'profile.role']), $this->userId);
            $reportService = new ReportService();
            $data = $reportService->getUserAttendanceReport($this->userId, $this->startDate, $this->endDate);

            $result = [
                    'employee_id' => $user->employee_id,
                    'name' => $user->profile->name,
                    'department' => $user->profile->department->name,
                    'role' => $user->profile->role->name,
                    'month' => $this->month,
                    'on_time' => $data['onTime']['value'] ?? 0,
                    'late' => $data['late']['value'] ?? 0,
                    'early_leave' => $data['earlyLeave']['value'] ?? 0,
                    'absent' => $data['absent']['value'] ?? 0,
                    'outside_location_check_in' => $data['checkInOutsideLocation']['value'] ?? 0,
                    'outside_location_check_out' => $data['checkOutOutsideLocation']['value'] ?? 0,
                ];


            return new Collection([$result]);
        }

        return new Collection ([]);
    }

    public function headings(): array
    {
        return [
            'employee_id',
            'name',
            'department',
            'role',
            'month',
            'on_time',
            'late',
            'early_leave',
            'absent',
            'outside_location_check_in',
            'outside_location_check_out',
        ];
    }

    public function title(): string
    {
        return 'summary';
    }

}
