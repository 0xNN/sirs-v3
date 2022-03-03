@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user'
])

@section('content')
<div class="content">
    <div id="app">
    <div class="card shadow">
        <div class="card-header">
        <a href="{{ route('createuser') }}" id="tambah-data" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></a>
        </div>
        <div class="card-body">
        <table id="dt-user" class="table table-sm table-bordered dt-responsive">
            <thead>
            <tr>
                <th>#</th>
                {{-- <th>Aksi</th> --}}
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Akses</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
    </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('sweetalert2') }}/css/sweetalert2.min.css">
@endpush

@push('scripts')
<script src="{{ asset('sweetalert2') }}/js/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $body = $("body");
    $(document).ready(function () {
        var table = $('#dt-user').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dt-user') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
                // {data: 'action', name: 'action', searchable: false, orderable: false},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'role_id', name: 'role_id'},
                {data: 'akses_id', name: 'akses_id'}
            ]
        });
    });
</script>
@endpush