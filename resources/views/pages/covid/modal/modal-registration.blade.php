<div class="modal fade" id="modalRegistration" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="form-registration" name="form-registration">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Informasi Registration') }}</h5>
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
                  <td>Registration No</td>
                  <td><input type="text" name="RegistrationNo" id="RegistrationNo" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Registration Date</td>
                  <td><input type="text" name="RegistrationDateTime" id="RegistrationDateTime" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Bed Code</td>
                  <td><input type="text" name="BedCode" id="BedCode" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Bed ID</td>
                  <td><input type="text" name="BedID" id="BedID" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Business Partner ID</td>
                  <td><input type="text" name="BusinessPartnerID" id="BusinessPartnerID" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Business Partner Name</td>
                  <td><input type="text" name="BusinessPartnerName" id="BusinessPartnerName" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Class Code</td>
                  <td><input type="text" name="ClassCode" id="ClassCode" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Class Name</td>
                  <td><input type="text" name="ClassName" id="ClassName" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Room Name</td>
                  <td><input type="text" name="RoomName" id="RoomName" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Service Unit</td>
                  <td><input type="text" name="ServiceUnitName" id="ServiceUnitName" class="form-control form-control-sm" readonly></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-6 col-md-6">
            <table class="table table-sm table-bordered">
              <tbody>
                <tr>
                  <td>Discharge</td>
                  <td><input type="text" name="IsDischarge" id="IsDischarge" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Discharge Date</td>
                  <td><input type="text" name="DischargeDateTime" id="DischargeDateTime" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Discharge Med Notes</td>
                  <td><textarea type="text" name="DischargeMedicalNotes" id="DischargeMedicalNotes" class="form-control form-control-sm" readonly></textarea></td>
                </tr>
                <tr>
                  <td>Discharge Notes</td>
                  <td><textarea type="text" name="DischargeNotes" id="DischargeNotes" class="form-control form-control-sm" readonly></textarea></td>
                </tr>
                <tr>
                  <td>Patient Type</td>
                  <td><input type="text" name="GCPatientInType" id="GCPatientInType" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Paramedic Name</td>
                  <td><input type="text" name="ParamedicName" id="ParamedicName" class="form-control form-control-sm" readonly></td>
                </tr>
                <tr>
                  <td>Present Illness</td>
                  <td><textarea name="PresentIllnessNotes" id="PresentIllnessNotes" class="form-control form-control-sm" readonly></textarea></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="closeRegistration" type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="submit" class="btn btn-sm btn-success" id="tombol-simpan" value="create">Simpan</button> --}}
      </div>
      </form>
    </div>
  </div>
</div>