<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function showAllSchedules()
    {
        $result = $this->scheduleService->findAllSchedules();
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

    public function createShift(Request $request)
    {
        $result = $this->scheduleService->createShift($request->all());
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }
}
