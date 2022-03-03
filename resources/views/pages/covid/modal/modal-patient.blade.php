<div class="modal fade" id="modalPatient" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="form-patient" name="form-patient">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Data Pasien') }}</h5>
        {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> --}}
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6 col-md-6">
            <table class="table table-sm table-bordered">
              <tbody>
                <tr>
                  <td>Medical No</td>
                  <td><input type="text" name="MedicalNo" id="MedicalNo" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Identitas</td>
                  <td><input type="text" name="SSN" id="SSN" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td><input type="text" name="PatientName" id="PatientName" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>No Hp</td>
                  <td><input type="text" name="MobilePhoneNo1" id="MobilePhoneNo1" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Tmp Lahir</td>
                  <td><input type="text" name="CityOfBirth" id="CityOfBirth" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Tgl lahir</td>
                  <td><input type="text" name="DateOfBirth" id="DateOfBirth" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>J. Kelamin</td>
                  <td><input type="text" name="GCSex" id="GCSex" class="form-control form-control-sm" readonly></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-6 col-md-6">
            <table class="table table-sm table-bordered">
              <tbody>
                <tr>
                  <td>Gol. Darah</td>
                  <td><input type="text" name="GCBloodType" id="GCBloodType" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Pendidikan</td>
                  <td><input type="text" name="GCEducation" id="GCEducation" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Pekerjaan</td>
                  <td><input type="text" name="GCOccupation" id="GCOccupation" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Status Menikah</td>
                  <td><input type="text" name="GCMaritalStatus" id="GCMaritalStatus" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Kebangsaan</td>
                  <td><input type="text" name="GCNationality" id="GCNationality" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Agama</td>
                  <td><input type="text" name="GCReligion" id="GCReligion" class="form-control form-control-sm" readonly></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="closePatient" type="button" class="btn btn-sm btn-secondary" aria-label="Close" data-dismiss="modal">Close</button>
        {{-- <button type="submit" class="btn btn-sm btn-success" id="tombol-simpan" value="create">Simpan</button> --}}
      </div>
      </form>
    </div>
  </div>
</div>