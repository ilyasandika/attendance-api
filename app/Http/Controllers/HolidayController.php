<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\HolidayRequest;
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
        $result = $this->holidayService->getHolidayList(
            $request->query('search'),
            (int)$request->query('rows')
        );
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createHoliday(HolidayRequest $request)
    {
        $request->validated();
        $result = $this->holidayService->createHoliday($request->all());
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    /**
     * Display the specified resource.
     */
    public function showHolidayById(Request $request)
    {

        $result = $this->holidayService->getHolidayById($request->route('id'));
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateHolidayById(HolidayRequest $request)
    {
        $request->validated();
        $result = $this->holidayService->updateHolidayById($request->all(), $request->route('id'));
        return helper::responseSuccessTry($result, "SUCCESS");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteHolidayById(Request $request)
    {
        $result = $this->holidayService->deleteHolidayById($request->route('id'));
        return helper::responseSuccessTry($result, "SUCCESS");
    }
}
