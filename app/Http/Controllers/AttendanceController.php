<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Attendance\AttendanceRequest;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function checkIn(AttendanceRequest $request)
    {
        $data = $request->validated();
        $file = $request->file('photo');
        $result = $this->attendanceService->handleAttendance($data, $file, Auth::user()->id);
        return helper::responseSuccessTry($result, "SUCCESS");
    }
    public function forceCheck(Request $request)
    {
        $result = $this->attendanceService->forceCheckoutAll();
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    public function showAttendanceList(Request $request)
    {
        $result = $this->attendanceService->getAttendanceList($request->query('search'));

        return helper::responseSuccessTry($result, "SUCCESS");
    }
    public function showAttendanceById(Request $request)
    {
        $result = $this->attendanceService->getAttendanceById($request->route('id'));
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    public function showAttendanceListByUserIdPath(Request $request)
    {
        $result = $this->attendanceService->getAttendanceListByUserId($request->route('id'));
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    public function showAttendanceListByUserLogin(Request $request)
    {
        $result = $this->attendanceService->getAttendanceListByUserId(Auth::user()->id, $request->query('search'));
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    public function showAttendanceListByDate(Request $request)
    {
        $result = $this->attendanceService->getAttendanceList(null, $request->query('date'), null, true);
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    public function showAttendanceByDateAndUserLogin(Request $request)
    {
//        dd($request->query('date'));
        $result = $this->attendanceService->getAttendanceList(null, $request->query('date'), Auth::user()->id);
        return helper::responseSuccessTry($result, $result ? "SUCCESS" : "NOT FOUND");
    }

}
