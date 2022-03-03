@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'alkes'
])

@section('content')
  <div class="content">
    <div id="app">
      <alert-notification message='Dikarenakan terdapat kesamaan pada service Alkes dan SDM maka dari itu menu Alkes masih tahap pengembangan!'></alert-notification>
      <div class="card shadow">
        <div class="card-header">
          <a href="{{ route('alkes.create') }}" id="tambah-data" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> / <i class="fas fa-edit"></i></a>
        </div>
        <div class="card-body">
          <table id="dt-alkes" class="table table-sm table-bordered dt-responsive">
            <thead>
              <tr>
                <th>#</th>
                <th>Aksi</th>
                <th>Status</th>
                <th>Kebutuhan</th>
                <th>Jumlah Exs</th>
                <th>Jumlah</th>
                <th>Jumlah Diterima</th>
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
      var table = $('#dt-alkes').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('alkes.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'status', name: 'status'},
            {data: 'kebutuhan', name: 'kebutuhan', searchable: false, orderable: false},
            {data: 'jumlah_eksisting', name: 'jumlah_eksisting'},
            {data: 'jumlah', name: 'jumlah'},
            {data: 'jumlah_diterima', name: 'jumlah_diterima'},
        ]
      });
    });
  </script>
    
@endpush