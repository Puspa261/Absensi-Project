<?php

namespace App\Http\Controllers;

use App\Models\JobTitles;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JobTitlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query_data = JobTitles::query();

            if ($request->sSearch) {
                $search_value = '%' . $request->sSearch . '%';
                $query_data = $query_data
                    ->where(function ($query) use ($search_value) {
                        $query
                            ->where('position', 'like', $search_value)
                            ->orWhere('division', 'like', $search_value);
                    });
            }

            // $data = $query_data->orderBy('day', 'asc')->get();
            $data = $query_data
                ->orderBy('division', 'asc')
                ->orderBy('position', 'asc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '
                    <div class="d-flex flex-row align-items-center justify-content-center">
                        <a class="btn btn-info show-btn me-1" href="' . route('job_titles.show', $row->id) . '"> 
                            <i class="bx bx-search-alt"></i>
                        </a>';

                    // if (Auth::user()->can('admin-edit')) {
                    $btn .= '<a class="btn btn-primary edit-btn me-1" href="' . route('job_titles.edit', $row->id) . '"> 
                                            <i class="bx bx-edit"></i>
                                        </a>';
                    // }

                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('job_titles.destroy', $row->id) . '"method="POST">';

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

        return view('job_titles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job_titles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        JobTitles::create($request->all());

        return redirect()->route('job_titles.index')
            ->with('success', 'Jabatan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobTitles = JobTitles::findOrFail($id);

        return view('job_titles.show', [
            'jobTitles' => $jobTitles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jobTitles = JobTitles::findOrFail($id);

        return view('job_titles.edit', compact('jobTitles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jobTitles = JobTitles::findOrFail($id);

        $jobTitles->update($request->all());

        return redirect()->route('job_titles.index')
            ->with('success', 'Jabatan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jobTitles = JobTitles::findOrFail($id);

        $jobTitles->delete();

        return redirect()->route('job_titles.index')
            ->with('success', 'Jabatan berhasil dihapus');
    }
}
