<?php

use App\Http\Controllers\LayoutController;
use App\Http\Controllers\ScheduleTemplatesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.main');
});

Route::get('dashboard', [LayoutController::class, 'dashboard'])->name('dashboard');

Route::resource('schedules', ScheduleTemplatesController::class);
