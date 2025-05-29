<?php

namespace App\Http\Controllers;

use App\Models\JobTitles;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query_data = User::query();

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
        // dd($request->all());
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

        $input = $request->all();

        $input['password'] = Hash::make($request->password);


        if ($image = $request->file('image')) {
            $destinationPath = 'imageUser/';
            $profileImage = $request->name . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $profileImage;
        } else {
            $input['image'] = 'ikon.jpg';
        }

        $user = User::create($input);
        // $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'Pegawai berhasil dibuat');
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

        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        if ($image = $request->file('image')) {
            $destinationPath = 'imageUser/';
            $profileImage = $request->name . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $profileImage;
        } else {
            unset($input['image']);
        }

        $user = user::find($id);
        $user->update($input);

        // DB::table('model_has_roles')->where('model_id', $id)->delete();
        // $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'Pegawai berhasil diperbarui');
    }


    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy($id)
    {
        $users = User::findOrFail($id);

        $users->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pegawai berhasil dihapus');
    }
}
