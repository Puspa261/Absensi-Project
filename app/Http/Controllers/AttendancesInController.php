<?php

namespace App\Http\Controllers;

use App\Models\AttendancesIn;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AttendancesInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query_data = AttendancesIn::with('user');

            if ($request->sSearch) {
                $search_value = '%' . $request->sSearch . '%';
                $query_data = $query_data
                    ->where(function ($query) use ($search_value) {
                        $query
                            ->where('date', 'like', $search_value)
                            ->orWhereHas('user', function ($query) use ($search_value) {
                                $query->where('name', 'like', $search_value);
                            });
                    });
            }

            $data = $query_data
                ->orderBy('date', 'desc')
                ->orderBy('time_in', 'desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '
                    <div class="d-flex flex-row align-items-center justify-content-center">
                        <a class="btn btn-info show-btn me-1" href="' . route('attendances_in.show', $row->id) . '"> 
                            <i class="bx bx-search-alt"></i>
                        </a>';

                    // if (Auth::user()->can('admin-edit')) {
                    $btn .= '<a class="btn btn-primary edit-btn me-1" href="' . route('attendances_in.edit', $row->id) . '"> 
                                            <i class="bx bx-edit"></i>
                                        </a>';
                    // }

                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('attendances_in.destroy', $row->id) . '"method="POST">';

                    // if (Auth::user()->can('admin-delete')) {
                    $btn .= csrf_field() . method_field('DELETE') . '
                                    <button type="button" class="btn btn-danger delete-btn" onclick="confirmDelete(' . $row->id . ')"> 
                                        <i class="bx bx-trash"></i>
                                    </button>';
                    // }

                    $btn .= '</form></div>';
                    return $btn;
                })
                ->addColumn('user', function ($row) {
                    return $row->user->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('attendances_in.index');
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

        $input = $request->all();
        $input['distance'] = $distance;

        // dd($request->all());

        AttendancesIn::create($input);

        return redirect()->route('attendances_in.index')
            ->with('success', 'Absen berhasil dibuat');
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

    // public function store(Request $request)
    // {
    //     $userLat = $request->latitude;
    //     $userLong = $request->longitude;

    //     // Koordinat kantor
    //     $officeLat = -2.9589504;
    //     $officeLong = 104.726528;

    //     // Panggil fungsi dari model
    //     $distance = AttendancesIn::calculateDistance($userLat, $userLong, $officeLat, $officeLong);

    //     // return response()->json([
    //     //     'lat1' => $userLat,
    //     //     'long1' => $userLong,
    //     //     'lat2' => $officeLat,
    //     //     'long2' => $officeLong,
    //     //     'distance' => number_format($distance, 2),
    //     //     'inside_zone' => $distance <= 0.05 // <= 50 meter
    //     // ]);

    //     // if ($distance <= 0.05) {
    //     //     return response()->json(['Berhasil']);
    //     // } else {
    //     //     return response()->json(['Gagal']);
    //     // }

    //     $input = $request->all();
    //     $input['distance'] = $distance;

    //     AttendancesIn::create($input);

    //     return redirect()->route('schedules.index')
    //         ->with('success', 'Jadwal berhasil dibuat');
    // }
}
