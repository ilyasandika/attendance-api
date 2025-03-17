<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Models\Department;
use App\Models\Role;
use App\Models\Schedule;
use Illuminate\Support\Carbon;

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


Route::get("/", function () {
    return "Hello World";
});


Route::get('/server-time', function () {
    return response()->json([
        'day' => Carbon::now()->translatedFormat('l'), // Nama hari dalam bahasa lokal
        'datetime' => Carbon::now()->toDateTimeString(), // Format Y-m-d H:i:s
    ]);
});


Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::post('/users', [AuthController::class, 'register']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/recent', [UserController::class, 'showLatest']);
    Route::get('/users/overview', [UserController::class, 'showOverview']);
    Route::get('/users/current', [UserController::class, 'showUserByUserLogin']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']); //without photo
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/schedules/shifts/details', [ScheduleController::class, 'showShiftDetailList']);
    Route::get('/schedules/shifts/{id}', [ScheduleController::class, 'showShiftById']);
    Route::put('/schedules/shifts/{id}', [ScheduleController::class, 'updateShiftById']);
    Route::delete('/schedules/shifts/{id}', [ScheduleController::class, 'deleteShiftById']);
    Route::post('/schedules/shifts', [ScheduleController::class, 'createShift']);
    Route::get('/schedules/shifts', [ScheduleController::class, 'showShiftList']);

    Route::get('/schedules/locations', [ScheduleController::class, 'showLocationList']);
    Route::get('/schedules/locations/all', [ScheduleController::class, 'showLocationNameList']);
    Route::post('/schedules/locations', [ScheduleController::class, 'createLocation']);
    Route::get('/schedules/locations/{id}', [ScheduleController::class, 'showLocationById']);
    Route::put('/schedules/locations/{id}', [ScheduleController::class, 'updateLocationById']);
    Route::delete('/schedules/locations/{id}', [ScheduleController::class, 'deleteLocationById']);

    Route::get('/schedules', [ScheduleController::class, 'showScheduleList']);
    Route::put('/schedules/{id}', [ScheduleController::class, 'updateSchedule']);

    Route::post('/attendances/check', [AttendanceController::class, 'checkIn']);
    Route::get('/attendances', [AttendanceController::class, 'showAttendanceList']);
    Route::get('/attendances/users', [AttendanceController::class, 'showAttendanceListByUserLogin']);
    Route::get('/attendances/users/{id}', [AttendanceController::class, 'showAttendanceListByUserIdPath']);
    Route::get('/attendances/{id}', [AttendanceController::class, 'showAttendanceById']);


    Route::get('/departments', [DepartmentController::class, 'getDepartmentList']);
    Route::get('/roles', [RoleController::class, 'getRoleList']);
});
