<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route umum (tanpa login)
Route::get('/', fn() => 'Hello World');
Route::get('/server-time', fn() => response()->json([
    'day' => Carbon::now()->Format('l'),
    'datetime' => Carbon::now()->toDateTimeString(),
]));


Route::post('/login', [AuthController::class, 'login']);
// Route yang butuh login
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout']);

    // =================== ADMIN ONLY ===================
    Route::middleware('role:admin')->group(function () {
        // User Management
        Route::post('/users', [AuthController::class, 'register']);
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::get('/users/recent', [UserController::class, 'getLatestUsers']);
        Route::get('/users/department', [UserController::class, 'getUsersByDepartment']);
        Route::get('/users/{id}', [UserController::class, 'getUserById'])->where('id', '[0-9]+');
        Route::delete('/users/{id}', [UserController::class, 'deleteUserById'])->where('id', '[0-9]+');
        Route::post('/users/{id}', [UserController::class, 'updateUserById'])->where('id', '[0-9]+');
        Route::get('/users/{id}', [UserController::class, 'getUserById'])->where('id', '[0-9]+');

        // Shift Management
        Route::get('/schedules/shifts', [ScheduleController::class, 'showShiftList']);
        Route::post('/schedules/shifts', [ScheduleController::class, 'createShift']);
        Route::put('/schedules/shifts/{id}', [ScheduleController::class, 'updateShiftById'])->where('id', '[0-9]+');
        Route::delete('/schedules/shifts/{id}', [ScheduleController::class, 'deleteShiftById'])->where('id', '[0-9]+');

        Route::get('/shifts', [ShiftController::class, 'getShifts']);
        Route::post('/shifts', [ShiftController::class, 'createShift']);
        Route::put('/shifts/{id}', [ShiftController::class, 'updateShiftById'])->where('id', '[0-9]+');
        Route::delete('/shifts/{id}', [ShiftController::class, 'deleteShiftById'])->where('id', '[0-9]+');

        // Location Management
        Route::get('/locations', [LocationController::class, 'getLocations']);
        Route::post('/locations', [LocationController::class, 'createLocation']);
        Route::get('/locations/{id}', [LocationController::class, 'getLocationById'])->where('id', '[0-9]+');
        Route::put('/locations/{id}', [LocationController::class, 'updateLocationById'])->where('id', '[0-9]+');
        Route::delete('/locations/{id}', [LocationController::class, 'deleteLocationById'])->where('id', '[0-9]+');

        // Holiday Management
        Route::post('/schedules/holidays', [HolidayController::class, 'createHoliday']);
        Route::put('/schedules/holidays/{id}', [HolidayController::class, 'updateHolidayById'])->where('id', '[0-9]+');
        Route::delete('/schedules/holidays/{id}', [HolidayController::class, 'deleteHolidayById'])->where('id', '[0-9]+');

        // Schedule
        Route::get('/schedules', [ScheduleController::class, 'showScheduleList']);
        Route::put('/schedules/{id}', [ScheduleController::class, 'updateSchedule'])->where('id', '[0-9]+');

        // Department
        Route::get('/departments', [DepartmentController::class, 'getDepartmentList']);
        Route::post('/departments', [DepartmentController::class, 'createDepartment']);
        Route::delete('/departments/{id}', [DepartmentController::class, 'deleteDepartment'])->where('id', '[0-9]+');
        Route::put('/departments/{id}', [DepartmentController::class, 'updateDepartment'])->where('id', '[0-9]+');
        Route::get('/departments/{id}', [DepartmentController::class, 'getDepartmentById'])->where('id', '[0-9]+');

        Route::post('/roles', [RoleController::class, 'createRole']);
        Route::delete('/roles/{id}', [RoleController::class, 'deleteRoleById'])->where('id', '[0-9]+');
        Route::put('/roles/{id}', [RoleController::class, 'updateRoleById'])->where('id', '[0-9]+');
        Route::get('/roles/{id}', [RoleController::class, 'getRoleById'])->where('id', '[0-9]+');


        //attendance
        Route::get('/attendances', [AttendanceController::class, 'showAttendanceList']);
        Route::get('/attendances/generate', [AttendanceController::class, 'generateBaseline']);
        Route::get('/attendances/force', [AttendanceController::class, 'forceCheck']);
        Route::get('/attendances/summary', [AttendanceController::class, 'showAttendanceSummary']);
        Route::get('/attendances/timeline', [AttendanceController::class, 'showAttendanceTimeLine']);
        Route::get('/attendances/users/{id}', [AttendanceController::class, 'showAttendanceListByUserIdPath'])->where('id', '[0-9]+');


        // Force Absen

    });

    // ============== SHARED (admin + employee) ==============
    Route::middleware('role:admin,employee')->group(function () {
        // View own profile
        Route::get('/users/current', [UserController::class, 'getCurrentUser']);
        Route::post('/users/edit', [UserController::class, 'updateCurrentUser']);

        // Attendance
        Route::post('/attendances/check', [AttendanceController::class, 'checkIn']);
        Route::get('/attendances/users', [AttendanceController::class, 'showAttendanceListByUserLogin']);
        Route::get('/attendances/date', [AttendanceController::class, 'showAttendanceByDateAndUserLogin']);
        Route::get('/attendances/{id}', [AttendanceController::class, 'showAttendanceById'])->where('id', '[0-9]+');

        // View data
        Route::get('/schedules/shifts/all', [ScheduleController::class, 'showShiftNameList']);
        Route::get('/schedules/shifts/{id}', [ScheduleController::class, 'showShiftById'])->where('id', '[0-9]+');
        Route::get('/shifts/me', [ShiftController::class, 'getUserShiftByUserLogin']);
        Route::get('/shifts/all', [ShiftController::class, 'getShiftDropdown']);
        Route::get('/shifts/{id}', [ShiftController::class, 'getShiftById'])->where('id', '[0-9]+');

        Route::get('/schedules/shifts/details', [ScheduleController::class, 'showShiftDetailList']);
        Route::get('/schedules/locations/all', [ScheduleController::class, 'showLocationNameList']);
        Route::get('/locations/all', [LocationController::class, 'getLocationDropdown']);
        Route::get('/schedules/locations/{id}', [ScheduleController::class, 'showLocationById'])->where('id', '[0-9]+');
        Route::get('/schedules/holidays', [HolidayController::class, 'showHolidayList']);
        Route::get('/schedules/holidays/{id}', [HolidayController::class, 'showHolidayById']);
        Route::get('/departments/all', [DepartmentController::class, 'getDepartmentDropdown']);
        Route::get('/roles', [RoleController::class, 'getRoleList']);
        Route::get('/roles/all', [RoleController::class, 'getRoleDropdown']);;

        //auth
        Route::get('/logout', [AuthController::class, 'logout']);

    });
});



