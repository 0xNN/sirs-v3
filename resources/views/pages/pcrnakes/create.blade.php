@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'pcrnakes'
])

@section('content')
  <div class="content">
    <div id="app">
      <form name="form-pcrnakes" id="form-pcrnakes">
        <div class="card shadow">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="row">
                  <div class="col">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control form-control-sm">
                  </div>
                  <div class="col">
                    <label for="paramedic">Paramedic</label>
                    <select name="paramedic" id="paramedic" class="form-control form-control-sm">
                      <option value="dokter_umum">Dokter Umum</option>
                      <option value="dokter_spesialis">Dokter Spesialis</option>
                      <option value="dokter_gigi">Dokter Gigi</option>
                      <option value="residen">Residen</option>
                      <option value="perawat">Perawat</option>
                      <option value="bidan">Bidan</option>
                      <option value="apoteker">Apoteker</option>
                      <option value="radiografer">Radiografer</option>
                      <option value="analis_lab">Analis Lab</option>
                      <option value="co_ass">CO Ass</option>
                      <option value="internship">Internship</option>
                      <option value="nakes_lainnya">Nakes Lainnya</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="jumlah_tenaga">Jumlah Tenaga</label>
                <input type="number" name="jumlah_tenaga" id="jumlah_tenaga" class="form-control form-control-sm" readonly>
              </div>
              <div class="col">
                <label for="sudah_periksa">Sudah Periksa</label>
                <input type="number" name="sudah_periksa" id="sudah_periksa" class="form-control form-control-sm" readonly>
              </div>
              <div class="col">
                <label for="hasil_pcr">Hasil PCR</label>
                <input type="number" name="hasil_pcr" id="hasil_pcr" class="form-control form-control-sm" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="rekap_jumlah_tenaga">Rekap Jumlah Tenaga</label>
                <input type="number" name="rekap_jumlah_tenaga" id="rekap_jumlah_tenaga" class="form-control form-control-sm" readonly>
              </div>
              <div class="col">
                <label for="rekap_jumlah_sudah_diperiksa">Rekap Jumlah Sudah Diperiksa</label>
                <input type="number" name="rekap_jumlah_sudah_diperiksa" id="rekap_jumlah_sudah_diperiksa" class="form-control form-control-sm" readonly>
              </div>
              <div class="col">
                <label for="rekap_jumlah_hasil_pcr">Rekap Jumlah Hasil PCR</label>
                <input type="number" name="rekap_jumlah_hasil_pcr" id="rekap_jumlah_hasil_pcr" class="form-control form-control-sm" readonly>
              </div>
            </div>
            {{-- <div class="row mt-3">
              <div class="col">
                <div class="form-check">
                  <label class="form-check-label" for="IsKirim">
                    <input type="checkbox" name="IsKirim" id="IsKirim">
                    Kirim ke Kemkes?
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
              </div>
            </div> --}}
          </div>
          <div class="card-footer">
            <button type="button" name="btn-simpan" id="btn-simpan" class="btn btn-sm btn-success">Simpan</button>
            {{-- <button type="button" name="btn-kirim" id="btn-kirim" class="btn btn-sm btn-info">Kirim</button> --}}
          </div>
        </div>
      </form>
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
    paramedic = '';
    jumlah_tenaga = 0;
    sudah_periksa = 0;
    hasil_pcr = 0;  

    tmpValue = {
      'dokter_umum': 0,
      'dokter_spesialis': 0,
      'dokter_gigi': 0,
      'residen': 0,
      'perawat': 0,
      'bidan': 0,
      'apoteker': 0,
      'radiografer': 0,
      'analis_lab': 0,
      'co_ass': 0,
      'internship': 0,
      'nakes_lainnya': 0,
    };

    function init() {
      sessionStorage.setItem('dokter_umum', 0);
      sessionStorage.setItem('dokter_spesialis', 0);
      sessionStorage.setItem('dokter_gigi', 0);
      sessionStorage.setItem('residen', 0);
      sessionStorage.setItem('perawat', 0);
      sessionStorage.setItem('bidan', 0);
      sessionStorage.setItem('apoteker', 0);
      sessionStorage.setItem('radiografer', 0);
      sessionStorage.setItem('analis_lab', 0);
      sessionStorage.setItem('co_ass', 0);
      sessionStorage.setItem('internship', 0);
      sessionStorage.setItem('nakes_lainnya', 0);
      sessionStorage.setItem('rekap_jumlah_tenaga',0);
      sessionStorage.setItem('rekap_jumlah_sudah_diperiksa',0);
      sessionStorage.setItem('rekap_jumlah_hasil_pcr',0);

      $('#paramedic > option').each(function() {
        keyTenaga = 'jumlah_tenaga_'+$(this).val();
        keyPeriksa = 'sudah_periksa_'+$(this).val();
        keyPcr = 'hasil_pcr_'+$(this).val();
        sessionStorage.setItem(keyTenaga, 0);
        sessionStorage.setItem(keyPeriksa, 0);
        sessionStorage.setItem(keyPcr, 0);
      })
    }

    function clearSession() {
      sessionStorage.clear();
    }

    function setSession(key, value) {
      sessionStorage.setItem(key, value);
    }

    function setRekap(key, value) {
      var jumlah = 0;
      tmpValue[paramedic] = value;
      for (const [key, val] of Object.entries(tmpValue)) {
        jumlah += parseInt(val);
      }
      sessionStorage.setItem(key, jumlah);
    }

    function enterKey(e) {
      if(e.which == 13) {
        $('#btn-simpan').focus();
        e.preventDefault();
      }
    }

    $(document).ready(function () {
      clearSession();
      init();
      $('#btn-simpan').click(function() {
        var obj = Object.keys(sessionStorage).reduce(function(obj, key) {
          obj[key] = sessionStorage.getItem(key);
          return obj
        }, {});

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
                data: {
                  data: obj,
                  tanggal: tanggal,
                  isKirim: isKirim,
                },
                url: "{{ route('pcrnakes.store') }}",
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
      $('#paramedic').change(function() {
        if($(this).val() != "-1") {
          $('#jumlah_tenaga').prop('readonly', false);
          $('#sudah_periksa').prop('readonly', false);
          $('#hasil_pcr').prop('readonly', false);
        } else {
          $('#jumlah_tenaga').prop('readonly', true);
          $('#sudah_periksa').prop('readonly', true);
          $('#hasil_pcr').prop('readonly', true);
        }
        paramedic = $(this).val();
      });
      $('#jumlah_tenaga').change(function() {
        jumlah_tenaga = $(this).val();
        var key = 'jumlah_tenaga_'+paramedic;
        setRekap($('#rekap_jumlah_tenaga').attr('id'), jumlah_tenaga);
        setSession(paramedic, jumlah_tenaga);
        setSession(key, jumlah_tenaga);
        $('#rekap_jumlah_tenaga').val(sessionStorage.getItem($('#rekap_jumlah_tenaga').attr('id')));
      });
      $('#sudah_periksa').change(function() {
        sudah_periksa = $(this).val();
        var key = 'sudah_periksa_'+paramedic;
        setRekap($('#rekap_jumlah_sudah_diperiksa').attr('id'), sudah_periksa);
        setSession(paramedic, sudah_periksa);
        setSession(key, sudah_periksa);
        $('#rekap_jumlah_sudah_diperiksa').val(sessionStorage.getItem($('#rekap_jumlah_sudah_diperiksa').attr('id')));
      });
      $('#hasil_pcr').change(function() {
        hasil_pcr = $(this).val();
        var key = 'hasil_pcr_'+paramedic;
        setRekap($('#rekap_jumlah_hasil_pcr').attr('id'), hasil_pcr);
        setSession(paramedic, hasil_pcr);
        setSession(key, hasil_pcr);
        $('#rekap_jumlah_hasil_pcr').val(sessionStorage.getItem($('#rekap_jumlah_hasil_pcr').attr('id')));
      });

      $('#jumlah_tenaga').keydown(function(e) {
        if(e.which == 9) {
          $('#sudah_periksa').val("");
          $('#sudah_periksa').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#paramedic').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
      $('#sudah_periksa').keydown(function(e) {
        if(e.which == 9) {
          $('#hasil_pcr').val("");
          $('#hasil_pcr').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#jumlah_tenaga').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
      $('#hasil_pcr').keydown(function(e) {
        if(e.which == 9) {
          $('#paramedic').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#sudah_periksa').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
      $('#paramedic').keydown(function(e) {
        if(e.which == 9) {
          $('#jumlah_tenaga').val("");
          $('#jumlah_tenaga').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#hasil_pcr').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
    });
  </script>
@endpush