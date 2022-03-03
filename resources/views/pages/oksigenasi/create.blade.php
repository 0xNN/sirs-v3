@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'oksigenasi'
])

@section('content')
  <div class="content">
    <div id="app">
      <div class="row">
        <div class="col">
          <form name="form-oksigenasi" id="form-oksigenasi">
            <div class="card shadow">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control form-control-sm">
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="p_cair">Pemakaian Oksigen Cair</label>
                    <input class="form-control form-control-sm" type="number" name="p_cair" id="p_cair" placeholder="Jumlah Oksigen Cair">
                  </div>
                  <div class="col">
                    <label for="satuan_p_cair">Satuan</label>
                    <select name="satuan_p_cair" id="satuan_p_cair" class="form-control form-control-sm">
                      <option value="m3">M3</option>
                      <option value="liter">Liter</option>
                      <option value="kg">KG</option>
                      <option value="ton">Ton</option>
                      <option value="galon">Galon</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="p_tabung_kecil">P Tabung Kecil</label>
                    <input type="number" name="p_tabung_kecil" id="p_tabung_kecil" class="form-control form-control-sm" placeholder="Pemakaian Tabung Kecil">
                  </div>
                  <div class="col">
                    <label for="p_tabung_sedang">P Tabung Sedang</label>
                    <input type="number" name="p_tabung_sedang" id="p_tabung_sedang" class="form-control form-control-sm" placeholder="Pemakaian Tabung Sedang">
                  </div>
                  <div class="col">
                    <label for="p_tabung_besar">P Tabung Besar</label>
                    <input type="number" name="p_tabung_besar" id="p_tabung_besar" class="form-control form-control-sm" placeholder="Pemakaian Tabung Besar">
                  </div>
                </div>
                <div class="row mt-5">
                  <div class="col">
                    <label for="k_isi_cair">Ketersediaan Oksigen Cair</label>
                    <input class="form-control form-control-sm" type="number" name="k_isi_cair" id="k_isi_cair" placeholder="Jumlah Ketersediaan Oksigen Cair">
                  </div>
                  <div class="col">
                    <label for="satuan_k_isi_cair">Satuan</label>
                    <select name="satuan_k_isi_cair" id="satuan_k_isi_cair" class="form-control form-control-sm">
                      <option value="m3">M3</option>
                      <option value="liter">Liter</option>
                      <option value="kg">KG</option>
                      <option value="ton">Ton</option>
                      <option value="galon">Galon</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="k_isi_tabung_kecil">K Isi Tabung Kecil</label>
                    <input type="number" name="k_isi_tabung_kecil" id="k_isi_tabung_kecil" class="form-control form-control-sm" placeholder="Ketersediaan Tabung Kecil">
                  </div>
                  <div class="col">
                    <label for="k_isi_tabung_sedang">K Isi Tabung Sedang</label>
                    <input type="number" name="k_isi_tabung_sedang" id="k_isi_tabung_sedang" class="form-control form-control-sm" placeholder="Ketersediaan Tabung Sedang">
                  </div>
                  <div class="col">
                    <label for="k_isi_tabung_besar">K Isi Tabung Besar</label>
                    <input type="number" name="k_isi_tabung_besar" id="k_isi_tabung_besar" class="form-control form-control-sm" placeholder="Ketersediaan Tabung Besar">
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button type="button" name="btn-simpan" id="btn-simpan" class="btn btn-sm btn-success">Simpan</button>
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

    $(document).ready(function () {
      $('#btn-simpan').click(function() {
        if($('#tanggal').val() == "") {
          Swal.fire({
            title: 'Tanggal Belum Diisi',
            icon: 'warning',
            showDenyButton: false,
            confirmButtonText: 'Tutup',
          })
        } else {
          var tanggal = $('#tanggal').val();
          var isKirim = $('#IsKirim').is(':checked');
          Swal.fire({
            title: 'Konfirmasi Penyimpanan Data?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Simpan',
            denyButtonText: 'Batal',
          }).then((result) => {
            if(result.isConfirmed) {
              $.ajax({
                beforeSend: function() {
                  $body.addClass('loading');
                },
                data: $('#form-oksigenasi').serialize(),
                url: "{{ route('oksigenasi.store') }}",
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
        }
      });
    });
  </script>
@endpush