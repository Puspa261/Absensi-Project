<?php

namespace App\Http\Controllers;

use App\Models\ScheduleTemplates;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function dashboard()
    {
        $day = ScheduleTemplates::all();
        // dd($day);
        return view('dashboard', compact('day'));
    }
}
