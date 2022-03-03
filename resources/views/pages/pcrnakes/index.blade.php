@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'pcrnakes'
])

@section('content')
  <div class="content">
    <div id="app">
      <div class="card shadow">
        <div class="card-header">
          <a href="{{ route('pcrnakes.create') }}" id="tambah-data" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> / <i class="fas fa-edit"></i></a>
        </div>
        <div class="card-body">
          <div class="row mb-2">
            <div class="col">
              <input type="date" name="tanggal" id="tanggal" class="form-control">
            </div>
          </div>
          <table id="dt-pcrnakes" class="table table-sm table-bordered dt-responsive">
            <thead>
              <tr>
                <th>#</th>
                <th>Aksi</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Jumlah Dokter Umum</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      {{-- <div class="card shadow mt-2">
        <pre></pre>
      </div> --}}
    </div>
  </div>
@endsection

@push('css')
  <link rel="stylesheet" href="{{ asset('sweetalert2') }}/css/sweetalert2.min.css">

  <style>
    pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
    .string { color: green; }
    .number { color: darkorange; }
    .boolean { color: blue; }
    .null { color: magenta; }
    .key { color: red; }
  </style>
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

    // $('pre').hide();
    $(document).ready(function () {
      var tanggal = $('#tanggal').val();
      var table = $('#dt-pcrnakes').DataTable({
        processing: true,
        serverSide: true,
        // select: true,
        // stateSave: true,
        // rowId: "tanggal",
        ajax: {
          beforeSend: function(request) {
            request.setRequestHeader('tanggal', tanggal);
          },
          url: "{{ route('pcrnakes.index') }}",
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'status', name: 'status'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'jumlah_tenaga_dokter_umum', name: 'jumlah_tenaga_dokter_umum'},
        ]
      });

      $('#tanggal').change(function() {
        tanggal = $(this).val();
        table.ajax.reload();
      });
      
      table.on('select', function ( e, dt, type, indexes ) {
        var rowData = table.rows( indexes ).data().toArray();
        var str = JSON.stringify(rowData[0], undefined, 4);
        output(syntaxHighlight(str));
      }).on('deselect', function ( e, dt, type, indexes ) {
        
      });
      function output(inp) {
        // $('pre').show();
        $('pre').html(inp);
      }

      function syntaxHighlight(json) {
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
          var cls = 'number';
          if (/^"/.test(match)) {
            if (/:$/.test(match)) {
              cls = 'key';
            } else {
              cls = 'string';
            }
          } else if (/true|false/.test(match)) {
            cls = 'boolean';
          } else if (/null/.test(match)) {
            cls = 'null';
          }
          return '<span class="' + cls + '">' + match + '</span>';
        });
      }

      $('body').on('click', '.btn-sinkron', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data PCR Nakes?',
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
              url: "{{ route('pcrnakes.send') }}",
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