<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendanceExportSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected ?int $userId;
    protected ?string $startDate;
    protected ?string $endDate;
    protected ?string $month;

    public function __construct(int $userId = null, string $startDate = null, string $endDate = null )
    {
        $date = Helper::getDateRangeOrDefaultFromString($startDate, $endDate);
        $this->userId = $userId;
        $this->startDate = $date['startDate'];
        $this->endDate = $date['endDate'];
        $this->month = $date['month'];
    }

    public function collection(): Collection
    {
         return Attendance::with('user.profile.department', 'user.profile.role')
             ->where('user_id', $this->userId)
             ->whereBetween('date', [$this->startDate, $this->endDate])
             ->get();
    }

    public function headings(): array
    {
        return [
            'employee_id',
            'name',
            'department',
            'role',
            'date',
            'check_in',
            'check_in_status',
            'check_in_comment',
            'check_in_outside_location',
            'check_out',
            'check_out_status',
            'check_out_outside_location',
            'check_out_comment',
        ];
    }

    public function map($row): array
    {
        return [
            $row->user->employee_id,
            $row->user->profile->name,
            $row->user->profile->department->name,
            $row->user->profile->role->name,
            Carbon::createFromTimestamp($row->date)->format('d-m-Y'),
            $row->check_in_time ? Carbon::createFromTimestamp($row->check_in_time)->format('H:i:s') : 0,
            $row->check_in_status,
            $row->check_in_comment,
            $row->check_in_outside_location ? "true" : "false",
            $row->check_out_time ? Carbon::createFromTimestamp($row->check_out_time)->format('H:i:s') : 0,
            $row->check_out_status,
            $row->check_out_outside_location ? "true" : "false",
            $row->check_out_comment,
        ];
    }

    public function title(): string
    {
        return 'Attendance';
    }

}
