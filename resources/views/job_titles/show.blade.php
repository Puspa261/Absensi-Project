@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center py-2">
                    <a href="{{ route('job_titles.index') }}" class="btn btn-icon back">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h5 class="card-title fw-semibold mt-2">Data Jabatan</h5>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Posisi</label>
                            <input class="form-control" value="{{ $jobTitles->position }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Divisi</label>
                            <input class="form-control" value="{{ $jobTitles->division }}" disabled>
                        </div>

                        <a href="{{ route('job_titles.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
