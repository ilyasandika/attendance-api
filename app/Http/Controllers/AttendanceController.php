<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function checkIn(Request $request)
    {
        $result = $this->attendanceService->createAttendance($request, Auth::user()->id);
        return (!$result['status']) ? Helper::responseError($result, "UNAUTHORIZED") : Helper::responseSuccess($result, "SUCCESS");
    }
}
