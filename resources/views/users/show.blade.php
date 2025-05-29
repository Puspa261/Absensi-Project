@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center py-2">
                    <a href="{{ route('users.index') }}" class="btn btn-icon back">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h5 class="card-title fw-semibold mt-2">Data Pegawai</h5>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input class="form-control" value="{{ $users->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input class="form-control" value="{{ $users->job->position }} {{ $users->job->division }}"
                                disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Pegawai</label>
                            <input class="form-control" value="{{ $users->employee_number }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" value="{{ $users->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <div class="card border" style="max-width: 14rem; max-height: 14rem;">
                                <img src="/imageUser/{{ $users->image }}" class="card-img" alt="image"
                                    style="max-width: 14rem; max-height: 14rem; object-fit: cover">
                            </div>
                        </div>

                        <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
