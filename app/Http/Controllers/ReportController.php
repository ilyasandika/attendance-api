<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExportSheet;
use App\Exports\MainExport;
use App\Helpers\Helper;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected $reportServices;


    public function __construct(ReportService $reportServices)
    {
        $this->reportServices = $reportServices;

    }

    public function getUserAttendanceReport(Request $request)
    {
        $data = $this->reportServices->getUserAttendanceReport(
            $request->route('id'),
            $request->query('start_date'),
            $request->query('end_date'));

        return Helper::responseSuccessTry($data, "SUCCESS");
    }

    public function getUserAttendanceReportByYear(Request $request)
    {
        $data = $this->reportServices->getUserAttendanceReportByYear(
            $request->route('id'),
            $request->route('year'));

        return Helper::responseSuccessTry($data, "SUCCESS");
    }

    public function getAvailableReportYears(Request $request)
    {
        $data = $this->reportServices->getAvailableReportYears(
            $request->query('id'));

        return Helper::responseSuccessTry($data, "SUCCESS");
    }

    public function getAttendanceReportExcel(Request $request)
    {
        $export = new MainExport(
            $request->route('id'),
            $request->query('start_date'),
            $request->query('end_date'));

        return Excel::download($export, 'attendance.xlsx');
    }
}
