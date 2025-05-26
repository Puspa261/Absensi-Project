@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center py-2">
                    <a href="{{ route('schedules.index') }}" class="btn btn-icon back">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h5 class="card-title fw-semibold mt-2">Tambah Data Jadwal</h5>
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
                        <form action="{{ route('schedules.store') }}" method="POST" autocomplete="on">
                            @csrf
                            <div class="mb-3">
                                <label for="day" class="form-label">Hari</label>
                                <select id="day" class="form-select" name="day" required>
                                    <option value="">Pilih Hari</option>
                                    @foreach ($days as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" name="start_time" class="form-control" id="start_time" required>
                            </div>

                            <div class="mb-3">
                                <label for="end_time" class="form-label">Waktu Selesai</label>
                                <input type="time" name="end_time" class="form-control" id="end_time" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
