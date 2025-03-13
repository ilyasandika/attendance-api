<?php


namespace App\Services;

use App\Helpers\Helper;
use App\Http\Resources\UserCollection;
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

        $data = [
            "id" => $user->id,
            "employeeId" => $user->employee_id,
            "employeeEmail" => $user->email,
            "employeeName" => $user->profile->name,
            "employeeGender" => $user->profile->gender,
            "employeeBirthDate" => strtotime($user->profile->birth_date),
            "employeePhoneNumber" => $user->profile->phone_number,
            "employeeRole" => $user->profile->role->name,
            "employeeDepartment" => $user->profile->department->name,
            "employeeShift" => $user->schedule->shift->name,
            "employeeWorkLocation" => $user->schedule->location->name,
            "employeeWhatsApp" => $user->profile->whatsapp,
            "employeeLinkedin" => $user->profile->linkedin,
            "employeeTelegram" => $user->profile->telegram,
            "employeeBiography" => $user->profile->Biography,
            "accountStatus" => $user->status,
        ];

        return Helper::returnSuccess($data);
    }

    public function updateById(Request $request)
    {
        $id = $request->route("id");
        $data = $request->all();
        $data['id'] = $id;

        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'employeeId' => 'required|string',
            'employeeName' => 'required|string|max:255',
            'employeeGender' => 'required|string|in:male,female',
            'employeeBirthDate' => 'required|integer',
            'employeePhoneNumber' => 'required|string|max:15',
            'employeeEmail' => 'required|string|email|max:255',
            'employeeRoleId' => 'required|integer',
            'employeeDepartmentId' => 'required|integer',
            'employeeShiftId' => 'required|integer',
            'employeeWorkLocationId' => 'required|integer',
            'employeePassword' => 'string|min:6',
            'employeeWhatsApp' => 'string',
            'employeeLinkedin' => 'string',
            'employeeTelegram' => 'string',
            'employeeBiography' => 'string',
            'accountStatus' => 'required|boolean',
            'profilePhoto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors()
            ];
        }

        $user = User::with('schedule.shift', 'schedule.location', 'profile.role', 'profile.department')->find($id);

        if (!$user) {
            return Helper::returnIfNotFound($user, "user not found");
        }
        DB::beginTransaction();
        try {
            $user->id = $id;
            $user->employee_id = $data["employeeId"];
            $user->email = $data["employeeEmail"];
            if ($data["employeePassword"]) {
                $user->password = Hash::make($data["employeePassword"]);
            }
            $user->status = $data["accountStatus"];
            $user->save();

            $user->profile->user_id = $id;
            $user->profile->role_id = $data["employeeRoleId"];
            $user->profile->department_id = $data["employeeDepartmentId"];
            $user->profile->name = $data["employeeName"];
            $user->profile->phone_number = $data["employeePhoneNumber"];
            $user->profile->whatsapp = $data["employeeWhatsApp"];
            $user->profile->linkedin = $data["employeeLinkedin"];
            $user->profile->telegram = $data["employeeTelegram"];
            $user->profile->biography = $data["employeeBiography"];
            $user->profile->birth_date = $data["employeeBirthDate"];
            $user->profile->gender = $data["employeeGender"];

            // if ($request->hasFile('profilePhoto')) {
            //     $file = $request->file('profilePhoto');
            //     $filename = $data['employee_id'] . '.' . $file->getClientOriginalExtension();
            //     $path = $file->storeAs('profile', $filename, "public");
            //     if ($user->profile->profile_photo_path) {
            //         Storage::disk('public')->delete($user->profile->profile_photo_path);
            //     }
            //     $user->profile->profile_photo_path = $path;
            // }
            $user->profile->save();


            $user->schedule->shift_id = $data['employeeShiftId'];
            $user->schedule->location_id = $data['employeeWorkLocationId'];
            $user->schedule->save();

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

    public function findOverview()
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
