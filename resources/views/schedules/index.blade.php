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
                <h4>Format Penjadwalan</h4>
            </div>
            <div class="pull-right">
                {{-- @can('admin-create') --}}
                <a class="btn btn-primary" href="{{ route('schedules.create') }}"> Tambahkan Jadwal</a>
                {{-- @endcan --}}
            </div>
        </div>

        <!-- Hoverable Table rows -->
        <div class="card">
            <h5 class="card-header">Data Jadwal</h5>
            <div class="table-responsive text-nowrap p-4">
                <table class="table table-hover data-table border">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Hari</th>
                            <th class="text-center">Jam Mulai</th>
                            <th class="text-center">Jam Selesai</th>
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
                ajax: "{{ route('schedules.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'day',
                        name: 'day',
                        className: 'text-center',
                    },
                    {
                        data: 'start_time',
                        name: 'start_time',
                        className: 'text-center',
                    },
                    {
                        data: 'end_time',
                        name: 'end_time',
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
