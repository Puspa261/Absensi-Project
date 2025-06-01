@extends('layouts.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="col-lg-12 margin-tb d-flex justify-content-between py-3">
            <div class="pull-left">
                <h4>Absen Masuk</h4>
            </div>
            <div class="pull-right">
                {{-- @can('admin-create') --}}
                <a class="btn btn-primary" href="{{ route('attendances_in.create') }}"> Tambahkan Data</a>
                {{-- @endcan --}}
            </div>
        </div>

        <!-- Hoverable Table rows -->
        <div class="card">
            <h5 class="card-header">Data Absen Masuk</h5>
            <div class="table-responsive text-nowrap p-4">
                <table class="table table-hover data-table border">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Pegawai</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jam Masuk</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--/ Hoverable Table rows -->

        {{-- <hr class="my-5" /> --}}
    </div>
@endsection
@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('attendances_in.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'user',
                        name: 'user',
                        className: 'text-center',
                    },
                    {
                        data: 'date',
                        name: 'date',
                        className: 'text-center',
                    },
                    {
                        data: 'time_in',
                        name: 'time_in',
                        className: 'text-center',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
