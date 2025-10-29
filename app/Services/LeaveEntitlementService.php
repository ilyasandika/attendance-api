<?php

namespace App\Services;


use App\Helpers\Helper;
use App\Http\Resources\LeaveEntitlementCollection;
use App\Http\Resources\LeaveEntitlementResource;
use App\Models\LeaveEntitlement;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;

class  LeaveEntitlementService
{
public function getLeaveEntitlements($search = null, int $rows = 10, $userId = null)
    {
        $query = LeaveEntitlement::query()->with([
            'user.profile.role',
            'user.profile.department',
            'leaves'
        ]);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('year', 'like', "%{$search}%")
                    ->orWhereHas('user.profile', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $leaveEntitlements = $query
            ->orderBy('year', 'desc')
            ->orderBy(
                User::select('employee_id')
                ->whereColumn('user_id', 'leave_entitlements.user_id')
                ->limit(1),
            )
            ->paginate($rows);
        return new LeaveEntitlementCollection($leaveEntitlements);

    }

    public function getLeaveEntitlementById (int $id) {
        $leaveEntitlement = Helper::findOrError(LeaveEntitlement::with([
            'user.profile.role',
            'user.profile.department'
        ]), $id);

        return new LeaveEntitlementResource($leaveEntitlement);
    }

    public function subLeaveEntitlement ($id, $count) {

    }

    public function createLeaveEntitlement ($data) {
        $leaveEntitlement = new LeaveEntitlement();
        $leaveEntitlement->user_id = $data['userId'];
        $leaveEntitlement->year = Carbon::now()->year;
        $leaveEntitlement->total_days = $data['totalDays'];
        $leaveEntitlement->save();
    }

    public function updateLeaveEntitlement ($data, $id) {
        $leaveEntitlement = Helper::findOrError(LeaveEntitlement::class, $id);
        $leaveEntitlement->total_days = $data['totalDays'];
        $leaveEntitlement->save();
    }

    public function generateLeaveEntitlement () {
        $users = User::get();
        foreach ($users as $user) {
            $leaveEntitlement = new LeaveEntitlement();
            $leaveEntitlement->user_id = $user->id;
            $leaveEntitlement->year = Carbon::now()->year;
            $leaveEntitlement->total_days = 12;
            $leaveEntitlement->save();
        }
    }
}
