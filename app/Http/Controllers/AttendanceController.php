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

    public function showAttendanceList(Request $request)
    {
        $result = $this->attendanceService->getAttendanceList();
        return (!$result['status']) ? Helper::responseError($result, "UNAUTHORIZED") : Helper::responseSuccess($result, "SUCCESS");
    }
    public function showAttendanceById(Request $request)
    {
        $result = $this->attendanceService->getAttendanceById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "UNAUTHORIZED") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showAttendanceListByUserIdPath(Request $request)
    {
        $result = $this->attendanceService->getAttendanceListByUserId($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "UNAUTHORIZED") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showAttendanceListByUserLogin(Request $request)
    {
        $result = $this->attendanceService->getAttendanceListByUserId(Auth::user()->id);
        return (!$result['status']) ? Helper::responseError($result, "UNAUTHORIZED") : Helper::responseSuccess($result, "SUCCESS");
    }
}
