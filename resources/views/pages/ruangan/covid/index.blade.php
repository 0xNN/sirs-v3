@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'ruangan'
])

@section('content')
  <div class="content">
    <div id="app">
      <div class="card shadow">
        <div class="card-header">
          <a href="{{ route('ruangan.create') }}" id="tambah-data" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> / <i class="fas fa-edit"></i></a>
        </div>
        <div class="card-body">
          <table id="dt-ruangan" class="table table-sm table-bordered dt-responsive nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>Aksi</th>
                <th>Status</th>
                <th>Kelas</th>
                <th>Ruangan</th>
                <th>Jumlah</th>
                {{-- <th>Terpakai</th> --}}
                <th>Terpakai Suspek</th>
                <th>Terpakai Konfirmasi</th>
                <th>Kosong</th>
              </tr>
            </thead>
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

    $(document).ready(function() {
      var table = $('#dt-ruangan').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('ruangan.covid') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'status', name: 'status', searchable: false, orderable: false},
            {data: 'tt', name: 'tt'},
            {data: 'ruang', name: 'ruang'},
            {data: 'jumlah', name: 'jumlah'},
            // {data: 'terpakai', name: 'terpakai'},
            {data: 'terpakai_suspek', name: 'terpakai_suspek'},
            {data: 'terpakai_konfirmasi', name: 'terpakai_konfirmasi'},
            {data: 'kosong', name: 'kosong'}
        ]
      });

      $('body').on('click', '.btn-sinkron', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data Ruangan?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Kirim',
          denyButtonText: 'Batal',
        }).then((result) => {
          if(result.isConfirmed) {
            $.ajax({
              data: {
                'id': id
              },
              url: "{{ route('ruangan.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.btn-sinkron').prop('disabled', false);
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
              }
            });
          }
        });
        console.log($(this).data('id'));
      });
    });
  </script>
@endpush