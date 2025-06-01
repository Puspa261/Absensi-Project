<?php

namespace App\Http\Controllers;

use App\Models\AttendancesIn;
use App\Models\User;
use Illuminate\Http\Request;

class AttendancesInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function create()
    {
        $users = User::all();
        return view('attendances_in.create', compact('users'));
        // dd('haloo');
    }

    public function store(Request $request)
    {
        $userLat = $request->latitude;
        $userLong = $request->longitude;

        // Koordinat kantor
        $officeLat = -2.9589504;
        $officeLong = 104.726528;

        // Panggil fungsi dari model
        $distance = AttendancesIn::calculateDistance($userLat, $userLong, $officeLat, $officeLong);

        // return response()->json([
        //     'lat1' => $userLat,
        //     'long1' => $userLong,
        //     'lat2' => $officeLat,
        //     'long2' => $officeLong,
        //     'distance' => number_format($distance, 2),
        //     'inside_zone' => $distance <= 0.05 // <= 50 meter
        // ]);

        if ($distance <= 0.05) {
            return response()->json(['Berhasil']);
        } else {
            return response()->json(['Gagal']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendancesIn $attendancesIn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendancesIn $attendancesIn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendancesIn $attendancesIn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendancesIn $attendancesIn)
    {
        //
    }
}
