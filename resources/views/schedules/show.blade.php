@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center py-2">
                    <a href="{{ route('schedules.index') }}" class="btn btn-icon back">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h5 class="card-title fw-semibold mt-2">Data Jadwal</h5>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Hari</label>
                            <input class="form-control" value="{{ $scheduleTemplates->day }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input class="form-control" value="{{ $scheduleTemplates->start_time }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input class="form-control" value="{{ $scheduleTemplates->end_time }}" disabled>
                        </div>

                        <a href="{{ route('schedules.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
