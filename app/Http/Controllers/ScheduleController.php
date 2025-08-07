<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\ScheduleService;
use Illuminate\Http\Request;


class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function showScheduleList(Request $request)
    {
        $result = $this->scheduleService->getScheduleList($request->query('search'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function UpdateSchedule(Request $request)
    {
        $result = $this->scheduleService->updateScheduleById($request->all(), $request->route('id'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function createLocation(Request $request)
    {
        $result = $this->scheduleService->createLocation($request->all());
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function showLocationList(Request $request)
    {
        $result = $this->scheduleService->getLocationList(false, $request->query('search'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }


    public function showLocationNameList(Request $request)
    {
        $result = $this->scheduleService->getLocationList(true);
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }


    public function showLocationById(Request $request)
    {
        $result = $this->scheduleService->getLocationById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function updateLocationById(Request $request)
    {
        $result = $this->scheduleService->updateLocationById($request->all(), $request->route('id'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function deleteLocationById(Request $request)
    {
        $result = $this->scheduleService->deleteLocationById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }


    public function createShift(Request $request)
    {
        $result = $this->scheduleService->createShift($request->all());
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function showShiftDetailList(Request $request)
    {
        $result = $this->scheduleService->getShiftList();
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function showShiftList(Request $request)
    {
        $result = $this->scheduleService->getShiftList(false, $request->query("search"));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function showShiftNameList(Request $request)
    {
        $result = $this->scheduleService->getShiftList(true);
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function showShiftById(Request $request)
    {
        $result = $this->scheduleService->getShiftById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function updateShiftById(Request $request)
    {
        $result = $this->scheduleService->updateShiftById($request->all(), $request->route('id'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function deleteShiftById(Request $request)
    {
        $result = $this->scheduleService->deleteShiftById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }
}
