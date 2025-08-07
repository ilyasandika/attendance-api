<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected HolidayService $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }
    public function showHolidayList(request $request)
    {
        $result = $this->holidayService->getHolidayList($request->query('search'));
        return (!$result['status']) ? Helper::responseError($result['data'], "UNAUTHORIZED") : Helper::responseSuccess($result['data'], "SUCCESS");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createHoliday(Request $request)
    {
        $result = $this->holidayService->createHoliday($request->all());

//        dd($result["data"]);
        return (!$result["status"]) ? Helper::responseError($result["errors"], "UNAUTHORIZED") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    /**
     * Display the specified resource.
     */
    public function showHolidayById(Request $request)
    {
        $result = $this->holidayService->getHolidayById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result['data'], "UNAUTHORIZED") : Helper::responseSuccess($result['data'], "SUCCESS");
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateHolidayById(Request $request)
    {
        $result = $this->holidayService->updateHolidayById($request->all(), $request->route('id'));
        return (!$result['status']) ? Helper::responseError($result['data'], "UNAUTHORIZED") : Helper::responseSuccess($result['data'], "SUCCESS");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteHolidayById(Request $request)
    {
        $result = $this->holidayService->deleteHolidayById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result['data'], "UNAUTHORIZED") : Helper::responseSuccess($result['data'], "SUCCESS");
    }
}
