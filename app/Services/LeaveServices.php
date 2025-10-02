<?php

namespace App\Services;


use App\Exceptions\FieldInUseException;
use App\Http\Resources\LeaveCollection;
use App\Http\Resources\LeaveResource;
use App\Models\Leave;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LeaveServices
{
    public function getLeaveList(int $userId = null, $search = null, $date = null, $status = [])
    {
        $query = Leave::query()->with([
            'user.profile.department',
            'user.profile.role',
            'approver.profile.department',
            'approver.profile.role'
        ]);

        $user = [];


        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('approver.profile', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user.profile', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($date && isset($date['start'], $date['end'])) {
            $start = Carbon::parse($date['start'])->startOfDay()->timestamp;
            $end = Carbon::parse($date['end'])->endOfDay()->timestamp;

            $query->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
            });
        }

        if ($status) {
           $query->whereIn('status', $status);
        }

        return new LeaveCollection($query->orderBy('created_at', 'desc')->paginate(10));
    }

    public function getLeaveById (int $id){
        $leave = Leave::with([
            'user.profile.department',
            'user.profile.role',
            'approver.profile.department',
            'approver.profile.role'
        ])->find($id);

        if (!$leave) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }
        return new LeaveResource($leave);

    }

    public function createLeave($data, $file)
    {

        $start = Carbon::parse($data['startDate'])->startOfDay()->timestamp;
        $end = Carbon::parse($data['endDate'])->endOfDay()->timestamp;

        $leave = new Leave();

        if (isset($file)) {
            $filename = $data['userId'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('leave_attachment', $filename, 'public');
            $leave->attachment = $path;
        }

        $leave->user_id = $data['userId'];
        $leave->reason = $data['reason'];
        $leave->start_date = $start;
        $leave->end_date = $end;
        $leave->status = 'pending';
        $leave->type = $data['type'];
        $leave->total_days = Carbon::createFromTimestamp($start)->diffInDays(Carbon::createFromTimestamp($end)) + 1;

        $leave->save();

        return;
    }

    public function updateLeave ($id, $data, $file) {
        $start = Carbon::parse($data['startDate'])->startOfDay()->timestamp;
        $end = Carbon::parse($data['endDate'])->endOfDay()->timestamp;

        $leave = $this->findOrError($id);

        if (isset($file)) {
            if (
                $leave->attachment &&
                Storage::disk('public')->exists($leave->attachment)
            ) {
                Storage::disk('public')->delete($leave->attachment);
            }

            $filename = $leave->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('leave_attachment', $filename, 'public');
            $leave->attachment = $path;
        }

        if ($leave->status !== "pending" && $leave !== "draft" ) {
            $leave->status = "pending";
        }

        $leave->reason = $data['reason'];
        $leave->start_date = $start;
        $leave->end_date = $end;
        $leave->type = $data['type'];
        $leave->total_days = Carbon::createFromTimestamp($start)->diffInDays(Carbon::createFromTimestamp($end)) + 1;

        $leave->save();
    }

    public function approveOrRejectLeave(int $approverId, int $leaveId, string $status, string $comment = null){
        $leave = $this->findOrError($leaveId);

        $leave->approver_id = $approverId;
        $leave->approved_at = Carbon::now()->timestamp;
        $leave->status = $status;
        if ($status === "rejected") {
            $leave->comment = $comment;
        }
        $leave->save();
    }

    public function cancelLeave($id){
        $leave = $this->findOrError($id);
        if ($leave->status === "approved" || $leave->status === "rejected" ) {
            throw new FieldInUseException(__('errorMessages.cannot_cancel_accepted_or_rejected'));
        }
        $leave->status = "draft";
        $leave->save();
        return;
    }

    public function deleteLeave(int $id){
        $leave = Leave::find($id);
        if (!$leave) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        if ($leave->status === "approved" || $leave->status === "rejected" ) {
            throw new FieldInUseException(__('errorMessages.cannot_delete_accepted_or_rejected'));
        }

        $leave->delete();
        return;
    }


    private function findOrError($id){
        $leave = Leave::find($id);
        if (!$leave) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }
        return $leave;
    }
}
