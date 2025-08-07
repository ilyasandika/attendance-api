<?php


namespace App\Services;

use App\Helpers\Helper;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public function findAll($search)
    {

        $query = User::query();

        if ($search) {
            $query->where("employee_id", "like", "%{$search}%")
                ->orWhere("email", "like", "%{$search}%")
                ->orWhereHas("profile", function ($query) use ($search) {
                    $query->where("name", "like", "%{$search}%");
                });
        }

        $user = new UserCollection($query->with("profile")->paginate(10));

        if (!$user) {
            return Helper::returnIfNotFound($user, "user not found");
        }

        return Helper::returnSuccess($user);
    }

    public function findById(int $id)
    {
        $user = User::with('schedule.shift', 'schedule.location', 'profile.role', 'profile.department')->find($id);

        if (!$user) {
            return Helper::returnIfNotFound($user, "user not found");
        }


        return Helper::returnSuccess(new UserResource($user));
    }

    public function updateById(Request $request, array $data, int $id)
    {
        $user = User::with('schedule.shift', 'schedule.location', 'profile.role', 'profile.department')->find($id);

        if (!$user) {
            return Helper::returnIfNotFound($user, "user not found");
        }
        DB::transaction(function () use ($request, $data, $user, $id) {
            //Update User
            $user->id = $id;
            $user->employee_id = $data['employeeId'];
            $user->email = $data['email'];
            $user->status = $data['status'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            //Update Profile
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

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
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

    public function deleteById(int $id)
    {
        $user = User::with('profile', 'schedule')->find($id);

        if (!$user) {
            return [
                'status' => false,
                'errors' => ["message" => "User not found"]
            ];
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

            return Helper::returnSuccess($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'errors' => ["message" => $e->getMessage()]
            ];
        }
    }

    public function findLatest()
    {
        $user = User::with('profile')->latest()->take(5)->get()->map(function ($user) {
            return [
                "id" => $user->id,
                "employeeId" => $user->employee_id,
                "employeeName" => $user->profile->name,
                "createdAt" => strtotime($user->created_at)
            ];
        });


        if ($user)
            return Helper::returnSuccess($user);
    }

    public function overviewByDepartment()
    {
        $user = UserProfile::with("department")->selectRaw('department_id, COUNT(*) as total_users')->groupBy('department_id')->get()->map(function ($user) {
            return [
                "departmentId" => $user->department_id,
                "departmentName" => $user->department->name,
                "totalUser" => $user->total_users
            ];
        });

        if (!$user) {
            return Helper::returnIfNotFound($user, "user not found");
        }
        return Helper::returnSuccess($user);
    }
}
