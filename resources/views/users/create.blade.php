@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center py-2">
                    <a href="{{ route('users.index') }}" class="btn btn-icon back">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h5 class="card-title fw-semibold mt-2">Tambah Data Pegawai</h5>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mx-4 mb-0">
                        <strong>Whoops!</strong> There were some problems with your
                        input.<br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data"
                            autocomplete="on">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nama"
                                    required>
                            </div>


                            <div class="mb-3">
                                <label for="job" class="form-label">Jabatan</label>
                                <select id="job" class="form-select" name="job" required>
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($jobs as $job)
                                        <option value="{{ $job['id'] }}">{{ $job['position'] }} {{ $job['division'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="employee_number" class="form-label">Nomor Pegawai</label>
                                <input type="number" name="employee_number" class="form-control" id="employee_number"
                                    placeholder="cth:001010101" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="cth:acbde@gmail.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">Confirm Password</label>
                                <input type="password" id="confirm-password" class="form-control" name="confirm-password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="confirm-password" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="basic-default-image">Image</label>
                                <input type="file" class="form-control" id="basic-default-image" name="image" />
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
