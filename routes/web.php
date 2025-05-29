<?php

use App\Http\Controllers\JobTitlesController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\ScheduleTemplatesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.main');
});

Route::get('dashboard', [LayoutController::class, 'dashboard'])->name('dashboard');

Route::resource('schedules', ScheduleTemplatesController::class);
Route::resource('users', UserController::class);
Route::resource('job_titles', JobTitlesController::class);
