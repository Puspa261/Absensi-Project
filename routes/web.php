<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendancesInController;
use App\Http\Controllers\AttendancesOutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\JobTitlesController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\ScheduleTemplatesController;
use App\Http\Controllers\UserController;
use App\Models\AttendancesIn;
use App\Models\AttendancesOut;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('dashboard', [LayoutController::class, 'dashboard'])->name('dashboard')->middleware('AuthCheck');;

// CRUD
Route::resource('schedules', ScheduleTemplatesController::class)->middleware('AuthCheck');;
Route::resource('users', UserController::class)->middleware('AuthCheck');;
Route::resource('job_titles', JobTitlesController::class)->middleware('AuthCheck');;
Route::resource('attendances_in', AttendancesInController::class)->middleware('AuthCheck');;
Route::resource('attendances_out', AttendancesOutController::class)->middleware('AuthCheck');;

// Login
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
