@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center py-2">
                    <a href="{{ route('job_titles.index') }}" class="btn btn-icon back">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h5 class="card-title fw-semibold mt-2">Tambah Data Jabatan</h5>
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
                        <form action="{{ route('job_titles.store') }}" method="POST" autocomplete="on">
                            @csrf
                            <div class="mb-3">
                                <label for="position" class="form-label">Posisi</label>
                                <input type="text" name="position" class="form-control" id="position"
                                    placeholder="cth: Instruktur" autofocus required>
                            </div>

                            <div class="mb-3">
                                <label for="division" class="form-label">Divisi</label>
                                <input type="text" name="division" class="form-control" id="division"
                                    placeholder="cth: Web Development (Opsional)">
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
