<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MainExport implements WithMultipleSheets
{

    protected ?int $userId;
    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct(int $userId = null, string $startDate = null, string $endDate = null)
    {
        $this->userId = $userId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            new AttendanceExportSheet($this->userId, $this->startDate, $this->endDate),
            new SummaryAttendanceExportSheet($this->userId, $this->startDate, $this->endDate),
        ];
    }
}
