<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportServices;

    public function __construct(ReportService $reportServices){
        $this->reportServices = $reportServices;
    }

    public function getUserAttendanceReport(Request $request)
    {
        $data = $this->reportServices->getUserAttendanceReport($request->route('id'));
        Return Helper::responseSuccessTry($data, "SUCCESS");
    }
}
