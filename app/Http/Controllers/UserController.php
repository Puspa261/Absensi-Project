<?php

namespace App\Http\Controllers;

use App\Models\JobTitles;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query_data = User::with('job');

            if ($request->sSearch) {
                $search_value = '%' . $request->sSearch . '%';
                $query_data = $query_data
                    ->where(function ($query) use ($search_value) {
                        $query
                            ->where('name', 'like', $search_value)
                            ->orWhere('employee_number', 'like', $search_value)
                            ->orWhereHas('job', function ($query) use ($search_value) {
                                $query->where('position', 'like', $search_value);
                            })
                            ->orWhereHas('job', function ($query) use ($search_value) {
                                $query->where('division', 'like', $search_value);
                            });
                    });
            }

            $data = $query_data->orderBy('name', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '
                    <div class="d-flex flex-row align-items-center justify-content-center">
                        <a class="btn btn-info show-btn me-1" href="' . route('users.show', $row->id) . '"> 
                            <i class="bx bx-search-alt"></i>
                        </a>';

                    // if (Auth::user()->can('admin-edit')) {
                    $btn .= '<a class="btn btn-primary edit-btn me-1" href="' . route('users.edit', $row->id) . '"> 
                                            <i class="bx bx-edit"></i>
                                        </a>';
                    // }

                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('users.destroy', $row->id) . '"method="POST">';

                    // if (Auth::user()->can('admin-delete')) {
                    $btn .= csrf_field() . method_field('DELETE') . '
                                    <button type="button" class="btn btn-danger delete-btn" onclick="confirmDelete(' . $row->id . ')"> 
                                        <i class="bx bx-trash"></i>
                                    </button>';
                    // }

                    $btn .= '</form></div>';
                    return $btn;
                })
                ->addColumn('job', function ($row) {
                    return $row->job->position . " " . $row->job->division;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobs = JobTitles::orderBy('division', 'asc')
            ->orderBy('position', 'asc')
            ->get();
        return view('users.create', compact('jobs'));
    }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    public function store(Request $request)
    {
        $request->validate([
            'employee_number' => 'numeric|unique:users,employee_number',
            'email' => 'email|unique:users,email',
            'password' => 'same:confirm-password',
            'image' => 'image|mimes:jpeg,png,jpg',
        ], [
            'employee_number.numeric' => 'Nomor pegawai wajib berisikan angka',
            'employee_number.unique' => 'Nomor pegawai sudah terdaftar',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        DB::beginTransaction();
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);

            // Default image jika tidak upload
            $filename = 'ikon.jpg';

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $request->name . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move(public_path('imageUser'), $filename);
            }

            $input['image'] = $filename;

            User::create($input);
            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Pegawai berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();

            // Hapus file kalau sudah terlanjur terupload
            if ($filename !== 'ikon.jpg' && File::exists(public_path('imageUser/' . $filename))) {
                File::delete(public_path('imageUser/' . $filename));
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    // /**
    //  * Display the specified resource.
    //  */
    public function show($id)
    {
        $users = User::findOrFail($id);

        return view('users.show', [
            'users' => $users
        ]);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    public function edit($id)
    {
        $users = User::findOrFail($id);

        $jobs = JobTitles::orderBy('division', 'asc')
            ->orderBy('position', 'asc')
            ->get();

        return view('users.edit', compact('users', 'jobs'));
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'employee_number' => 'numeric|unique:users,employee_number,' . $id,
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'nullable|same:confirm-password',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ], [
            'employee_number.numeric' => 'Nomor pegawai wajib berisikan angka',
            'employee_number.unique' => 'Nomor pegawai sudah terdaftar',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        DB::beginTransaction();
        try {
            $users = User::findOrFail($id);
            $input = $request->all();

            // Handle password
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, ['password']);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $request->name . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move(public_path('imageUser'), $filename);

                // Hapus gambar lama jika bukan default
                if ($users->image && $users->image !== 'ikon.jpg') {
                    $oldPath = public_path('imageUser/' . $users->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $input['image'] = $filename;
            }

            $users->update($input);
            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Pegawai berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();

            // Hapus gambar baru jika upload berhasil tapi DB gagal
            if (isset($filename) && File::exists(public_path('imageUser/' . $filename))) {
                File::delete(public_path('imageUser/' . $filename));
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }


    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Pastikan bukan file default
        if ($user->image && $user->image !== 'ikon.jpg') {
            $imagePath = public_path('imageUser/' . $user->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
