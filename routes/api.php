<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Models\Schedule;

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

Route::post('/users', [AuthController::class, 'register']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/recent', [UserController::class, 'showLatest']);
Route::get('/users/overview', [UserController::class, 'showOverview']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']); //without photo
Route::delete('/users/{id}', [UserController::class, 'destroy']);



Route::get('/schedules', [ScheduleController::class, 'showAllSchedules']);
