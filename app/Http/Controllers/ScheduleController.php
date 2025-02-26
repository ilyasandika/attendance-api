<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Shift;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function showScheduleList()
    {
        $result = $this->scheduleService->getScheduleList();
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function UpdateSchedule(Request $request)
    {
        $result = $this->scheduleService->updateScheduleById($request->all(), $request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function createLocation(Request $request)
    {
        $result = $this->scheduleService->createLocation($request->all());
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showLocationList(Request $request)
    {
        $result = $this->scheduleService->getLocationList();
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showLocationById(Request $request)
    {
        $result = $this->scheduleService->getLocationById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function updateLocationById(Request $request)
    {
        $result = $this->scheduleService->updateLocationById($request->all(), $request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function deleteLocationById(Request $request)
    {
        $result = $this->scheduleService->deleteLocationById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }


    public function createShift(Request $request)
    {
        $result = $this->scheduleService->createShift($request->all());
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showShiftDetailList(Request $request)
    {
        $result = $this->scheduleService->getShiftList();
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showShiftList(Request $request)
    {
        return response()->json([
            "status" => 200,
            "message" => "SUCCESS",
            "data" => Shift::get()
        ]);
    }

    public function showShiftById(Request $request)
    {
        $result = $this->scheduleService->getShiftById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function updateShiftById(Request $request)
    {
        $result = $this->scheduleService->updateShiftById($request->all(), $request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function deleteShiftById(Request $request)
    {
        $result = $this->scheduleService->deleteShiftById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }
}
