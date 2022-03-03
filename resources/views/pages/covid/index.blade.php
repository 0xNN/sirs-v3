@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'covid'
])

@section('content')
    @include('pages.covid.modal.modal-patient')
    @include('pages.covid.modal.modal-diagnosa')
    @include('pages.covid.modal.modal-registration')
    @include('pages.covid.modal.modal-sync')
    <div class="content">
      <div id="app">
        <div class="card shadow">
          <div class="card-header">
            <div class="float-left">
              {{ __('Data Covid') }}
            </div>
            <div class="float-right">
              <button id="get-data-sphaira" class="btn btn-sm btn-primary"><i class="nc-icon nc-cloud-download-93"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="card card-nav-tabs card-plain">
                  <div class="card-header card-header-danger">
                      <div class="nav-tabs-navigation">
                          <div class="nav-tabs-wrapper">
                              <ul class="nav nav-tabs" data-tabs="tabs">
                                  <li class="nav-item">
                                      <a class="nav-link active" href="#code" data-toggle="tab">Diagnosis</a>
                                  </li>
                                  <li class="nav-item">
                                      <a class="nav-link" href="#petunjuk" data-toggle="tab">Petunjuk</a>
                                  </li>
                                  <li class="nav-item">
                                      <a class="nav-link" href="#api" data-toggle="tab">Sim RS</a>
                                  </li>
                              </ul>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <div class="tab-content">
                          <div class="tab-pane active" id="code">
                            @include('pages.covid.layouts.card-covid')
                          </div>
                          <div class="tab-pane" id="petunjuk">
                            @include('pages.covid.layouts.card-petunjuk')
                          </div>
                          <div class="tab-pane" id="api">
                            @include('pages.covid.layouts.card-sphaira')
                          </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <table id="dt-datacovid" class="table table-sm table-bordered dt-responsive nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Aksi</th>
                  <th>Discharge</th>
                  <th>Nama</th>
                  <th>No RM</th>
                  <th>Reg No</th>
                  <th>Tgl Reg</th>
                  {{-- <th>Diagnosa</th> --}}
                  <th>Room</th>
                  <th>Class</th>
                  <th>Service Unit</th>
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
  <script type="text/javascript">
    $(function() {
      var table = $('#dt-datacovid').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('covid.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'IsDischarge', name: 'IsDischarge'},
            {data: 'PatientName', name: 'PatientName'},
            {data: 'MedicalNo', name: 'MedicalNo'},
            {data: 'RegistrationNo', name: 'RegistrationNo'},
            {data: 'RegistrationDateTime', name: 'RegistrationDateTime'},
            {data: 'RoomID', name: 'RoomID'},
            {data: 'ClassCode', name: 'ClassCode'},
            {data: 'ServiceUnitID', name: 'ServiceUnitID'},
        ]
      });

      $('#closePatient').click(function() {
        $('#modalPatient').modal('hide');
      });

      $('#closeRegistration').click(function() {
        $('#modalRegistration').modal('hide');
      });

      $('body').on('click', '.get-sphaira', function() {
        console.log($(this).data('id'));
        console.log($(this).attr('registration-no'));
        var registrationno = $(this).attr('registration-no');
        registrationno = registrationno.replace('/','-').replace('/','-');
        var medicalno = $(this).data('id');
        $.get('registration/registrationno/'+registrationno+'/medicalno/'+medicalno, function(data) {
          if(data.data != null) {
            
          } else {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: data.message,
              showConfirmButton: false,
              timer: 1500
            });
          }
        });
      });

      $('body').on('click', '.registrationno', function() {
        console.log($(this).data('id'));
        var registrationno = $(this).data('id');
        registrationno = registrationno.replace('/','-').replace('/','-');
        console.log(registrationno);
        $.get('registration/registrationno/'+registrationno, function(data) {
          if(data.data != null) {
            $('#RegistrationNo').val(data.data.RegistrationNo);
            $('#RegistrationDateTime').val(data.data.RegistrationDateTime);
            $('#BedCode').val(data.data.BedCode);
            $('#BedID').val(data.data.BedID);
            $('#BusinessPartnerID').val(data.data.BusinessPartnerID);
            $('#BusinessPartnerName').val(data.data.BusinessPartnerName);
            $('#ClassCode').val(data.data.ClassCode);
            $('#ClassName').val(data.data.ClassName);
            $('#RoomName').val(data.data.RoomName);
            $('#ServiceUnitName').val(data.data.ServiceUnitName);
            $('#IsDischarge').val(data.data.IsDischarge);
            $('#DischargeDateTime').val(data.data.DischargeDateTime);
            $('#DischargeMedicalNotes').val(data.data.DischargeMedicalNotes);
            $('#DischargeNotes').val(data.data.DischargeNotes);
            $('#GCPatientInType').val(data.data.GCPatientInType);
            $('#ParamedicName').val(data.data.ParamedicName);
            $('#PresentIllnessNotes').val(data.data.PresentIllnessNotes);
            $('#modalRegistration').modal('show');
          } else {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: data.message,
              showConfirmButton: false,
              timer: 1500
            });
          }
        });
      });

      $('body').on('click','.medicalno', function() {
        console.log($(this).data('id'));
        var medicalno = $(this).data('id');
        $.get('patient/medicalno/'+medicalno, function (data) {
          if(data.data != null) {
            $('#MedicalNo').val(data.data.MedicalNo);
            $('#SSN').val(data.data.SSN);
            $('#PatientName').val(data.data.PatientName);
            $('#MobilePhoneNo1').val(data.data.MobilePhoneNo1);
            $('#CityOfBirth').val(data.data.CityOfBirth);
            $('#DateOfBirth').val(data.data.DateOfBirth);
            $('#GCSex').val(data.data.GCSex);
            $('#GCEducation').val(data.data.GCEducation);
            $('#GCMaritalStatus').val(data.data.GCMaritalStatus);
            $('#GCNationality').val(data.data.GCNationality);
            $('#GCReligion').val(data.data.GCReligion);
            $('#GCOccupation').val(data.data.GCOccupation);
            $('#GCBloodType').val(data.data.GCBloodType);
            $('#modalPatient').modal('show'); //modal tampil
          } else {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: data.message,
              showConfirmButton: false,
              timer: 1500
            });
          }
        });
      })
    });
  </script>
@endpush