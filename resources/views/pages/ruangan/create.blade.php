@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'ruangan'
])

@section('content')
  <div class="content">
    <div id="app">
      <div class="row">
        <div class="col-sm-6">
          <table id="dt-ruangan" class="table table-sm table-bordered">
            <thead>
              <tr>
                <th>#</th>
                {{-- <th>Aksi</th> --}}
                <th>Class Code</th>
                <th>Class Name</th>
                <th>Short/Initial</th>
                {{-- <th>Level</th> --}}
                <th>Active</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="col-sm-6">
          <table id="dt-serviceunit" class="table table-sm table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>ID</th>
                <th>Service Unit</th>
                <th>Short/Initial</th>
                <th>Active</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <table id="dt-room" class="table table-sm table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>RoomID</th>
                <th>Room Name</th>
                <th>Active</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="col-sm-12">
          <div class="card shadow">
            <div class="card-body">
              <form name="form-class" id="form-class">
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="ClassCode">Class Code</label>
                      <input type="text" name="ClassCode" id="ClassCode" class="form-control form-control-sm" readonly>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="ServiceUnitID">Service Unit</label>
                      <input type="text" name="ServiceUnitID" id="ServiceUnitID" class="form-control form-control-sm" readonly>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="RoomID">Room ID</label>
                      <input type="text" name="RoomID" id="RoomID" class="form-control form-control-sm" readonly>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="card-footer">
              <button type="button" id="cek-bed" name="cek-bed" class="cek-bed btn btn-sm btn-success"> Bed Check</button>
            </div>
          </div>
          <form name="form-simpan-bed" id="form-simpan-bed">
            <div class="card shadow">
              <div class="card-header">
                Ketersediaan Tempat Tidur
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="ClassApi">Class (Kemkes)</label>
                      <select name="ClassApi" id="ClassApi" class="form-control"></select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="ClassName">Class Name</label>
                      <input type="text" id="ClassName" name="ClassName" class="form-control form-control-sm" readonly>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label id="LabelJumlahRuang" for="JumlahRuang">Jumlah Ruang</label>
                      <input type="number" name="JumlahRuang" id="JumlahRuang" class="form-control form-control-sm">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="RoomName">Room Name (Nama Ruangan)</label>
                      <input type="text" id="RoomName" name="RoomName" class="form-control form-control-sm">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="ServiceUnitName">Service Unit Name</label>
                      <input type="text" id="ServiceUnitName" name="ServiceUnitName" class="form-control form-control-sm" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="TotalBed">Total TT</label>
                      <input type="number" class="form-control form-control-sm" name="TotalBed" id="TotalBed">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="X0116">Tidak Digunakan Lagi</label>
                      <input type="number" class="form-control form-control-sm" name="X0116" id="X0116">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="B0116">Block</label>
                      <input type="number" class="form-control form-control-sm" name="B0116" id="B0116">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="C0116">Dibersihkan</label>
                      <input type="number" class="form-control form-control-sm" name="C0116" id="C0116">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="O0116">Ditempati</label>
                      <input type="number" class="form-control form-control-sm" name="O0116" id="O0116">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="P0016">Rencana Pelepasan</label>
                      <input type="number" class="form-control form-control-sm" name="P0116" id="P0116">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="R0116">Ready</label>
                      <input type="number" class="form-control form-control-sm" name="R0116" id="R0116">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="W0116">Ingin ditransfer</label>
                      <input type="number" class="form-control form-control-sm" name="W0116" id="W0116">
                    </div>
                  </div>
                </div>
                @if (auth()->user()->akses_id == 1)
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="terpakai_suspek">Terpakai Suspek (Khusus Covid)</label>
                      <input type="number" class="form-control form-control-sm" name="terpakai_suspek" id="terpakai_suspek" value="0">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="terpakai_konfirmasi">Terpakai Konfirmasi (Khusus Covid)</label>
                      <input type="number" class="form-control form-control-sm" name="terpakai_konfirmasi" id="terpakai_konfirmasi" value="0">
                    </div>
                  </div>
                </div>
                @endif
                <div class="row">
                  <div class="col">
                    <div class="form-check">
                      <label class="form-check-label" for="IsUpdate">
                        <input type="checkbox" name="IsUpdate" id="IsUpdate" {{ auth()->user()->akses_id == 1 ? 'checked': '' }}>
                        Update yang Sudah Ada?
                        <span class="form-check-sign">
                          <span class="check"></span>
                        </span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="RuanganLama">Data Ruangan yang Pernah Input (Kemkes)</label>
                      <select name="RuanganLama" id="RuanganLama" class="form-control" disabled="disabled"></select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                @if (auth()->user()->akses_id == 1)
                <a href="{{ route('ruangan.covid') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>
                @endif
                @if (auth()->user()->akses_id == 0)
                <a href="{{ route('ruangan.noncovid') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>
                @endif
                <button type="button" id="btn-save-bed" name="btn-save-bed" class="btn btn-sm btn-info">Simpan <i class="fas fa-save"></i></button>
              </div>
            </div>
          </form>
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

    // $(document).on({
    //     ajaxStart: function() { $body.addClass("loading");    },
    //     ajaxStop: function() { $body.removeClass("loading"); }    
    // });

    $(document).ready(function() {
      var ClassCode = $('#ClassCode').val();
      var ServiceUnitID = $('#ServiceUnitID').val();
      var cek = "{{ auth()->user()->akses_id }}";

      var table = $('#dt-ruangan').DataTable({
        select: true,
        stateSave: true,
        rowId: "ClassCode",
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('ruangan.create') }}",
          data: {
            "tableData": "class",
          },
        },
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            // {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'ClassCode', name: 'ClassCode'},
            {data: 'ClassName', name: 'ClassName'},
            {data: 'ShortName', name: 'ShortName'},
            // {data: 'GCClassRL', name: 'GCClassRL'},
            {data: 'IsActive', name: 'IsActive'},
        ]
      });

      var tableService = $('#dt-serviceunit').DataTable({
        select: true,
        stateSave: true,
        rowId: "ServiceUnitID",
        processing: true,
        serverSide: true,
        ajax: {
          beforeSend: function(request) {
            request.setRequestHeader('ClassCode', ClassCode);
          },
          url: "{{ route('ruangan.create') }}",
          data: {
            "tableData": "serviceunit",
          },
        },
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            // {data: 'action', name: 'action', searchable: false, orderable: false},
            // {data: 'ClassCode', name: 'ClassCode'},
            {data: 'ServiceUnitID', name: 'ServiceUnitID'},
            {data: 'ServiceUnitName', name: 'ServiceUnitName'},
            {data: 'ShortName', name: 'ShortName'},
            {data: 'IsActive', name: 'IsActive'},
        ]
      });

      var tableRoom = $('#dt-room').DataTable({
        select: true,
        stateSave: true,
        rowId: "RoomID",
        processing: true,
        serverSide: true,
        ajax: {
          beforeSend: function(request) {
            request.setRequestHeader('ClassCode', ClassCode);
            request.setRequestHeader('ServiceUnitID', ServiceUnitID);
          },
          url: "{{ route('ruangan.create') }}",
          data: {
            "tableData": "room",
          },
        },
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            // {data: 'action', name: 'action', searchable: false, orderable: false},
            // {data: 'ClassCode', name: 'ClassCode'},
            {data: 'RoomID', name: 'RoomID'},
            {data: 'RoomName', name: 'RoomName'},
            {data: 'IsActive', name: 'IsActive'},
        ]
      });


      table.on('select', function ( e, dt, type, indexes ) {
        var rowData = table.rows( indexes ).data().toArray();
        $('#ClassCode').val(rowData[0].ClassCode);
        ClassCode = rowData[0].ClassCode;
        tableService.ajax.reload();
        tableRoom.ajax.reload();
      }).on('deselect', function ( e, dt, type, indexes ) {
        $('#ClassCode').val("");
        ClassCode = "";
        tableService.ajax.reload();
        tableRoom.ajax.reload();
      });

      tableService.on('select', function ( e, dt, type, indexes ) {
        var rowData = tableService.rows( indexes ).data().toArray();
        $('#ServiceUnitID').val(rowData[0].ServiceUnitID);
        ServiceUnitID = rowData[0].ServiceUnitID;
        tableRoom.ajax.reload();
      }).on('deselect', function ( e, dt, type, indexes ) {
        $('#ServiceUnitID').val("");
        ServiceUnitID = "";
        tableRoom.ajax.reload();
      });

      tableRoom.on('select', function ( e, dt, type, indexes ) {
        var rowData = tableRoom.rows( indexes ).data().toArray();
        $('#RoomID').val(rowData[0].RoomID);
      }).on('deselect', function ( e, dt, type, indexes ) {
        $('#RoomID').val("");
      });

      $('#btn-save-bed').click(function() {
        var IsUpdate = $('#IsUpdate').is(':checked');
        var ClassCode = $('#ClassCode').val();
        var ServiceUnitID = $('#ServiceUnitID').val();
        var RoomID = $('#RoomID').val();
        $.ajax({
          beforeSend: function(request) {
            $body.addClass("loading");
            request.setRequestHeader('IsUpdate', IsUpdate);
            request.setRequestHeader('ClassCode', ClassCode);
            request.setRequestHeader('ServiceUnitID', ServiceUnitID);
            request.setRequestHeader('RoomID', RoomID);
          },
          data: $('#form-simpan-bed').serialize(),
          url: "{{ route('ruangan.savebed') }}",
          type: "POST",
          success: function(data) {
            if(data.icon == 'error') {
              Swal.fire({
                position: 'center',
                icon: data.icon,
                title: data.message,
                showConfirmButton: true,
              });
            }
            if(data.icon == 'success') {
              Swal.fire({
                position: 'center',
                icon: data.icon,
                title: data.message,
                showConfirmButton: false,
                timer: 1500
              });
            }
          },
          complete: function() {
            $body.removeClass("loading");
          },
          error: function(data) {
            console.log(data);
          }
        });
      });

      $('.cek-bed').click(function() {
        var ClassCode = $('#ClassCode').val();
        var ServiceUnitID = $('#ServiceUnitID').val();
        var RoomID = $('#RoomID').val();
        $.ajax({
          data: { 
            "ClassCode": ClassCode,
            "ServiceUnitID": ServiceUnitID,
            "RoomID": RoomID,
          },
          url: "{{ route('ruangan.cek-bed') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            if(data.icon == 'error') {
              Swal.fire({
                position: 'center',
                icon: data.icon,
                title: data.message,
                showConfirmButton: true,
              });
            }
            if(data.icon == 'success') {
              $('#TotalBed').val(data.data.total_bed);
              $('#JumlahRuang').val(data.data.jumlah_ruang);
              $('#B0116').val(data.data.status.B0116);
              $('#C0116').val(data.data.status.C0116);
              $('#O0116').val(data.data.status.O0116);
              $('#P0116').val(data.data.status.P0116);
              $('#R0116').val(data.data.status.R0116);
              $('#W0116').val(data.data.status.W0116);
              $('#X0116').val(data.data.status.X0116);
              $('#RoomName').val(data.data.room.RoomName);
              $('#ServiceUnitName').val(data.data.serviceunit.ServiceUnitName);
              $('#ClassName').val(data.data.class.ClassName);
              if(cek == 1) {
              $('#terpakai_konfirmasi').val(data.data.status.O0116);
              }
              console.log(data);
            }
          },
        });
      });

      $('#ClassApi').select2({
        placeholder: "-- PILIH --",
        ajax: {
          url: "{{ route('ruangan.classapi') }}",
          dataType: "json",
          type: "POST",
          delay: 250,
          data: function (params) {
            return {
              search: params.term // search term
            };
          },
          processResults: function(data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });

      $('#IsUpdate').change(function() {
        if(cek == 0) {
          if($(this).prop('checked')) {
            $('#RuanganLama').prop('disabled', false);
            $('#ClassApi').prop('disabled', true);
          } else {
            $('#RuanganLama').prop('disabled', true);
            $('#ClassApi').prop('disabled', false);
            // $("#RuanganLama").val('').trigger('change');
          }
        } else {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Maaf! hanya untuk Akses Non Covid',
            showConfirmButton: true,
          });
          $(this).prop('checked', true);
        }
      });

      $('#RuanganLama').change(function() {
        var id = $(this).val();
        var text = $(this).select2('data')[0].text;
        $('#RoomName').val(text.split("-")[1]);
      });

      $('#RuanganLama').select2({
        placeholder: "-- PILIH --",
        ajax: {
          url: "{{ route('ruangan.ruanganlama') }}",
          dataType: "json",
          type: "POST",
          delay: 250,
          data: function (params) {
            return {
              search: params.term // search term
            };
          },
          processResults: function(data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
      // $('#ServiceUnitID').select2({
      //   placeholder: "-- PILIH --",
      //   ajax: {
      //     url: "{{ route('ruangan.serviceunit') }}",
      //     dataType: "json",
      //     type: "POST",
      //     delay: 250,
      //     data: function (params) {
      //       return {
      //         search: params.term // search term
      //       };
      //     },
      //     processResults: function(data) {
      //       return {
      //         results: data
      //       };
      //     },
      //     cache: true
      //   }
      // });

      if($('#IsUpdate').is(':checked')) {
        $('#ClassApi').prop('disabled', true);
        $('#RuanganLama').prop('disabled', false);
      } else {
        $('#ClassApi').prop('disabled', false);
        $('#RuanganLama').prop('disabled', true);
      }
    });
  </script>
@endpush
