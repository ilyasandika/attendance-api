<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\LeaveEntitlementService;
use Illuminate\Http\Request;

class LeaveEntitlementController extends Controller
{
    protected LeaveEntitlementService $leaveEntitlementService;

    public function __construct(LeaveEntitlementService $leaveEntitlementService)
    {
        $this->leaveEntitlementService = $leaveEntitlementService;
    }

    public function getLeaveEntitlements(Request $request) {
        $data = $this->leaveEntitlementService->getLeaveEntitlements(
            $request->query('search'),
            (int)$request->query('rows'),
        );
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getLeaveEntitlementByUserId(Request $request) {
        $data = $this->leaveEntitlementService->getLeaveEntitlements(
            null,
            $request->query('rows'),
            $request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getLeaveEntitlementById(Request $request) {
        $data = $this->leaveEntitlementService->getLeaveEntitlementById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function generateLeaveEntitlement() {
        $this->leaveEntitlementService->generateLeaveEntitlement();
        return Helper::responseSuccessTry([], __('successMessages.generate_success'));
    }


}
