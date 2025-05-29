<?php

namespace App\Http\Controllers;

use App\Models\ScheduleTemplates;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query_data = ScheduleTemplates::query();

            if ($request->sSearch) {
                $search_value = '%' . $request->sSearch . '%';
                $query_data = $query_data
                    ->where(function ($query) use ($search_value) {
                        $query
                            ->where('day', 'like', $search_value);
                    });
            }

            $data = $query_data->orderBy('day', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '
                    <div class="d-flex flex-row align-items-center justify-content-center">
                        <a class="btn btn-info show-btn me-1" href="' . route('schedules.show', $row->id) . '"> 
                            <i class="bx bx-search-alt"></i>
                        </a>';

                    // if (Auth::user()->can('admin-edit')) {
                    $btn .= '<a class="btn btn-primary edit-btn me-1" href="' . route('schedules.edit', $row->id) . '"> 
                                            <i class="bx bx-edit"></i>
                                        </a>';
                    // }

                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('schedules.destroy', $row->id) . '"method="POST">';

                    // if (Auth::user()->can('admin-delete')) {
                    $btn .= csrf_field() . method_field('DELETE') . '
                                    <button type="button" class="btn btn-danger delete-btn" onclick="confirmDelete(' . $row->id . ')"> 
                                        <i class="bx bx-trash"></i>
                                    </button>';
                    // }

                    $btn .= '</form></div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('schedules.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('schedules.create', compact('days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ScheduleTemplates::create($request->all());

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $scheduleTemplates = ScheduleTemplates::findOrFail($id);

        return view('schedules.show', [
            'scheduleTemplates' => $scheduleTemplates
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $scheduleTemplates = ScheduleTemplates::findOrFail($id);

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('schedules.edit', compact('scheduleTemplates', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $scheduleTemplates = ScheduleTemplates::findOrFail($id);

        $scheduleTemplates->update($request->all());

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $scheduleTemplates = ScheduleTemplates::findOrFail($id);

        $scheduleTemplates->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }
}
