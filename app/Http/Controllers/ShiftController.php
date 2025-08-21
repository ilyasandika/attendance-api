<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Shift\ShiftRequest;
use App\Services\ShiftServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    protected ShiftServices $shiftServices;

    public function __construct(ShiftServices $shiftServices)
    {
        $this->shiftServices = $shiftServices;
    }

    public function getShifts(Request $request) {
        $data = $this->shiftServices->getShiftList(false, $request->query('search'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getShiftDropdown() {
        $data = $this->shiftServices->getShiftList(true);
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getShiftById(Request $request) {
        $data = $this->shiftServices->getShiftById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function createShift(ShiftRequest $request) {
        $data = $request->validated();
        $data = $this->shiftServices->createShift($data);
        return Helper::responseSuccessTry($data, __('successMessages.create_success'));
    }

    public function updateShiftById(ShiftRequest $request) {
        $data = $request->validated();
        $data = $this->shiftServices->updateShiftById($data, $request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.update_success'));
    }

    public function getUserShiftByUserLogin(Request $request) {
        $data = $this->shiftServices->getUserShiftByUserId(Auth::user()->id);
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function deleteShiftById(Request $request) {
        $data = $this->shiftServices->deleteShiftById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.delete_success'));
    }
}
