<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Leave\LeaveRequest;
use App\Http\Requests\Leave\RejectLeaveRequest;
use App\Services\LeaveServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    protected LeaveServices $leaveServices;

    public function __construct(LeaveServices $leaveServices)
    {
        $this->leaveServices = $leaveServices;
    }

    public function getLeaveList (Request $request)
    {
        $date = [
          'start' => $request->query('start_date'),
          'end' => $request->query('end_date')
        ];

        $data = $this->leaveServices->getLeaveList(
            $request->query('search'),
            (int)$request->query('rows'),
            null,
            $date,
            $request->query('status'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getLeaveListByUserId (Request $request)
    {
        $date = [
            'start' => $request->query('start_date'),
            'end' => $request->query('end_date')
        ];

        $data = $this->leaveServices->getLeaveList(
            $request->query('search'),
            (int)$request->query('rows'),
            $request->route('id'),
            $date,
            $request->query('status'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getLeaveById (Request $request)
    {
        $data = $this->leaveServices->getLeaveById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function createLeave (LeaveRequest $request)
    {
        $data = $request->validated();
        $file  = $request->file('attachment');
        $data['userId'] = Auth::user()->id;
        $this->leaveServices->createLeave($data, $file);
        return Helper::responseSuccessTry([], __('successMessages.create_success'));
    }

    public function updateLeave (LeaveRequest $request)
    {
        $data = $request->validated();
        $file  = $request->file('attachment');
        $this->leaveServices->updateLeave($request->route('id'), $data, $file);
        return Helper::responseSuccessTry([], __('successMessages.update_success'));
    }

    public function approveLeave(Request $request) {
        $this->leaveServices->approveOrRejectLeave(Auth::user()->id,$request->route('id'), "approved");
        return Helper::responseSuccessTry([], __('successMessages.update_success'));
    }

    public function rejectLeave(RejectLeaveRequest $request){
        $data = $request->validated();
        $this->leaveServices->approveOrRejectLeave(Auth::user()->id, $request->route('id'), "rejected", $data["comment"]);
        return Helper::responseSuccessTry([], __('successMessages.update_success'));
    }

    public function cancelLeave(Request $request, Leave $leave){
        $this->authorize('cancel', $leave);
        $this->leaveServices->cancelLeave($request->route('id'));
        return Helper::responseSuccessTry([], __('successMessages.update_success'));
    }

    public function deleteLeave (Request $request)
    {
        $this->leaveServices->deleteLeave($request->route('id'));
        return Helper::responseSuccessTry([], __('successMessages.delete_success'));
    }
}
