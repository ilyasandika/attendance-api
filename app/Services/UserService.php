<?php

namespace App\Services;

use App\Http\Resources\AllUserCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{
    public function getUsers($search = null, $all = false)
    {
        $query = User::query()->with('profile');

        if ($search) {
            $query->where("employee_id", "like", "%{$search}%")
                ->orWhere("email", "like", "%{$search}%")
                ->orWhereHas("profile", function ($query) use ($search) {
                    $query->where("name", "like", "%{$search}%");
                });
        }

        if ($all) {
            return new AllUserCollection($query->get());
        }

        return new UserCollection($query->paginate(10));
    }

    public function getUserById(int $id)
    {
        $user = User::with('schedule.shift', 'schedule.location', 'profile.role', 'profile.department')->find($id);

        if (!$user) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        if($user->photo) {
            $user->photo = url("storage/{$user->photo}");
        }

        return new UserResource($user);
    }

    public function updateUserById(array $data, int $id)
    {
        $user = User::with('schedule.shift', 'schedule.location', 'profile.role', 'profile.department')->find($id);

        if (!$user) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        DB::transaction(function () use ($data, $user, $id) {
            // Update User
            $user->id = $id;
            $user->employee_id = $data['employeeId'];
            $user->email = $data['email'];
            $user->status = $data['status'];
            $user->role = $data['roleAccount'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            // Update Profile
            $user->profile->user_id = $id;
            $user->profile->role_id = $data['roleId'];
            $user->profile->department_id = $data['departmentId'];
            $user->profile->name = $data['name'];
            $user->profile->phone_number = $data['phoneNumber'];
            $user->profile->birth_date = $data['birthDate'];
            $user->profile->gender = $data['gender'];
            $user->profile->whatsapp = $data['whatsapp'] ?? null;
            $user->profile->linkedin = $data['linkedin'] ?? null;
            $user->profile->telegram = $data['telegram'] ?? null;
            $user->profile->biography = $data['biography'] ?? null;

            if (isset($data['photo'])) {
                $file = $data['photo'];
                $filename = $data['employeeId'] . '_' . time() . '.' . $file->getClientOriginalExtension();

                if (
                    $user->profile->profile_picture_path &&
                    Storage::disk('public')->exists($user->profile->profile_picture_path)
                ) {
                    Storage::disk('public')->delete($user->profile->profile_picture_path);
                }

                $path = $file->storeAs('profile', $filename, 'public');
                $user->profile->profile_picture_path = $path;
            }

            $user->profile->save();

            // Update Schedule
            $user->schedule->shift_id = $data['shiftId'];
            $user->schedule->location_id = $data['locationId'];
            $user->schedule->save();
        });

        return $user->fresh(['schedule.shift', 'schedule.location', 'profile.role', 'profile.department']);
    }

    public function deleteUserById(int $id)
    {
        $user = User::with('profile', 'schedule')->find($id);

        if (!$user) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        DB::beginTransaction();
        try {
            if ($user->profile->profile_photo_path) {
                Storage::disk('public')->delete($user->profile->profile_photo_path);
            }

            $user->profile->delete();
            $user->schedule->delete();
            $user->delete();

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getLatestUsers()
    {
        return User::with('profile')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    "id" => $user->id,
                    "employeeId" => $user->employee_id,
                    "employeeName" => $user->profile->name,
                    "createdAt" => strtotime($user->created_at)
                ];
            });
    }

    public function getUsersByDepartment()
    {
        return UserProfile::with("department")
            ->selectRaw('department_id, COUNT(*) as total_users')
            ->groupBy('department_id')
            ->get()
            ->map(function ($user) {
                return [
                    "departmentId" => $user->department_id,
                    "departmentName" => $user->department->name,
                    "totalUser" => $user->total_users
                ];
            });
    }
}
