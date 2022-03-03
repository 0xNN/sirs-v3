@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'oksigenasi'
])

@section('content')
  <div class="content">
    <div id="app">
      <div class="card shadow">
        <div class="card-header">
          <a href="{{ route('oksigenasi.create') }}" id="tambah-data" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> / <i class="fas fa-edit"></i></a>
        </div>
        <div class="card-body">
          <div class="row mb-2">
            <div class="col">
              <input type="date" name="tanggal" id="tanggal" class="form-control">
            </div>
          </div>
          <table id="dt-oksigenasi" class="table table-sm table-bordered dt-responsive">
            <thead>
              <tr>
                <th>#</th>
                <th>Aksi</th>
                <th>Status</th>
                <th>Tanggal</th>
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
      var tanggal = $('#tanggal').val();
      var table = $('#dt-oksigenasi').DataTable({
        processing: true,
        serverSide: true,
        // select: true,
        // stateSave: true,
        // rowId: "tanggal",
        ajax: {
          beforeSend: function(request) {
            request.setRequestHeader('tanggal', tanggal);
          },
          url: "{{ route('oksigenasi.index') }}",
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'status', name: 'status'},
            {data: 'tanggal', name: 'tanggal'},
        ]
      });

      $('#tanggal').change(function() {
        tanggal = $(this).val();
        table.ajax.reload();
      });

      $('body').on('click', '.btn-sinkron', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data Oksigenasi?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Kirim',
          denyButtonText: 'Batal',
        }).then((result) => {
          if(result.isConfirmed) {
            $.ajax({
              beforeSend: function() {
                $body.addClass('loading');
              },
              data: {
                'id': id
              },
              url: "{{ route('oksigenasi.send') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                if(data.icon == 'error') {
                  Swal.fire({
                    position: 'center',
                    icon: data.icon,
                    title: data.message,
                    showConfirmButton: true
                  });
                } else {
                  Swal.fire({
                    position: 'center',
                    icon: data.icon,
                    title: data.message,
                    showConfirmButton: true,
                  }).then((result) => {
                    if(result.isConfirmed) {
                      location.reload();
                    }
                  });
                }
              },
              complete: function() {
                $body.removeClass('loading');
              }
            });
          }
        });
        console.log($(this).data('id'));
      });
    });
  </script>
@endpush
