@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'sdm'
])

@section('content')
  <div class="content">
    <div id="app">
      <div class="row">
        <div class="col">
          <table id="dt-specialty" class="table table-sm table-bordered dt-responsive" style="width: 100%">
            <thead>
              <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="col">
          <table id="dt-paramedic" class="table table-sm table-bordered dt-responsive" style="width: 100%">
            <thead>
              <tr>
                <th>#</th>
                {{-- <th>ParamedicID</th> --}}
                <th>Specialty</th>
                <th>Type</th>
                <th>Jumlah</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="row mt-2">
        
      </div>
      <div class="row mt-2">
        <div class="col">
          <div class="form-group">
            <label for="SpecialtyCode">Specialty Code</label>
            <input type="text" name="SpecialtyCode" id="SpecialtyCode" class="form-control form-control-sm" readonly>
          </div>
        </div>
      </div>
      {{-- <div class="row mt-2">
        <div class="col">
          <table id="dt-paramedictype" class="table table-sm table-bordered dt-responsive">
            <thead>
              <tr>
                <th>#</th>
                <th>Code</th>
                <th>Type</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div> --}}
      <div class="row">
        <div class="col">
          <div class="card shadow-none">
          <form name="form-sdm" id="form-sdm">
            <div class="card-header">
              Info Jumlah Paramedic
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="kebutuhan">Kebutuhan</label>
                    <select name="kebutuhan" id="kebutuhan" class="form-control form-control-sm"></select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="jumlah_eksisting">Jumlah Eksisting</label>
                    <input type="number" name="jumlah_eksisting" id="jumlah_eksisting" class="form-control form-control-sm">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control form-control-sm" value="0">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="jumlah_diterima">Jumlah Diterima</label>
                    <input type="number" name="jumlah_diterima" id="jumlah_diterima" class="form-control form-control-sm" value="0">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-check">
                    <label class="form-check-label" for="IsUpdate">
                      <input type="checkbox" name="IsUpdate" id="IsUpdate">
                      Update yang Sudah Ada?
                      <span class="form-check-sign">
                        <span class="check"></span>
                      </span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="button" name="btn-simpan" id="btn-simpan" class="btn btn-sm btn-success">Simpan</button>
            </div>
          </form>
          </div>
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
      var SpecialtyCode = $('#SpecialtyCode').val();
      var table = $('#dt-paramedictype').DataTable({
        select: true,
        stateSave: true,
        rowId: "GeneralCodeID",
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('sdm.create') }}",
          data: {
            'tableData': 'paramedictype'
          }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'GeneralCodeID', name: 'GeneralCodeID'},
            {data: 'GeneralCodeName1', name: 'GeneralCodeName1'},
        ]
      });

      var tableSpecialty = $('#dt-specialty').DataTable({
        select: true,
        stateSave: true,
        rowId: "SpecialtyCode",
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('sdm.create') }}",
          data: {
            'tableData': 'specialty'
          }
        },
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'SpecialtyCode', name: 'SpecialtyCode'},
            {data: 'SpecialtyName1', name: 'SpecialtyName1'},
        ]
      });

      var tableParamedic = $('#dt-paramedic').DataTable({
        select: true,
        stateSave: true,
        // rowId: "ParamedicID",
        processing: true,
        serverSide: true,
        ajax: {
          beforeSend: function(request) {
            request.setRequestHeader('SpecialtyCode', SpecialtyCode);
          },
          url: "{{ route('sdm.create') }}",
          data: {
            'tableData': 'paramedic'
          }
        },
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            // {data: 'ParamedicID', name: 'ParamedicID'},
            {data: 'SpecialtyCode', name: 'SpecialtyCode'},
            {data: 'GCParamedicType', name: 'GCParamedicType'},
            {data: 'Jumlah', name: 'Jumlah'}
        ]
      });

      tableSpecialty.on('select', function ( e, dt, type, indexes ) {
        var rowData = tableSpecialty.rows( indexes ).data().toArray();
        console.log(rowData);
        $('#SpecialtyCode').val(rowData[0].SpecialtyCode);
        SpecialtyCode = rowData[0].SpecialtyCode;
        tableParamedic.ajax.reload();
      }).on('deselect', function ( e, dt, type, indexes ) {
        $('#SpecialtyCode').val("");
        SpecialtyCode = "";
        tableParamedic.ajax.reload();
      });

      tableParamedic.on('select', function ( e, dt, type, indexes ) {
        var rowData = tableParamedic.rows( indexes ).data().toArray();
        $('#jumlah_eksisting').val(rowData[0].Jumlah);
      }).on('deselect', function ( e, dt, type, indexes ) {
        $('#jumlah_eksisting').val("");
      });

      $('#kebutuhan').select2({
        placeholder: "-- PILIH --",
        ajax: {
          url: "{{ route('sdm.classapi') }}",
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
        if($(this).prop('checked')) {
          var id_kebutuhan = $('#kebutuhan').val();
          $.ajax({
            url: "{{ route('sdm.getsdm') }}",
            data: {
              "id_kebutuhan": id_kebutuhan
            },
            type: "POST",
            success: function(data) {
              // $('#jumlah_eksisting').val(data.jumlah_eksisting);
              $('#jumlah').val(data.jumlah);
              $('#jumlah_diterima').val(data.jumlah_diterima);
            }
          });
        } else {
          $('#jumlah').val("");
          $('#jumlah_diterima').val("");
        }
      });

      $('#btn-simpan').click(function() {
        var IsUpdate = $('#IsUpdate').is(':checked');
        $.ajax({
          url: "{{ route('sdm.store') }}",
          beforeSend: function(request) {
            $body.addClass("loading");
            request.setRequestHeader('IsUpdate', IsUpdate);
          },
          data: $('#form-sdm').serialize(),
          type: "POST",
          success: function(data) {
            Swal.fire({
              position: 'center',
              icon: data.icon,
              title: data.message,
              showConfirmButton: true,
            });
          },
          complete: function() {
            $body.removeClass("loading");
          }
        })
      })
    });
  </script>
@endpush