<div class="modal fade" id="modalSync" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="form-sync" name="form-sync">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Simpan (Sistem) & Lapor (RS Online)') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <table class="table table-sm table-bordered">
              <tbody>
                <tr>
                  <td>Kewarganegaraan</td>
                  <td><input type="text" readonly></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="submit" class="btn btn-sm btn-success" id="tombol-simpan" value="create">Simpan</button> --}}
      </div>
      </form>
    </div>
  </div>
</div>