@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'nakesterinfeksi'
])

@section('content')
  <div class="content">
    <div id="app">
      <form name="form-nakesterinfeksi" id="form-nakesterinfeksi">
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
                      <option value="co_ass">CO Ass</option>
                      <option value="residen">Residen</option>
                      <option value="intership">Intership</option>
                      <option value="dokter_spesialis">Dokter Spesialis</option>
                      <option value="dokter_umum">Dokter Umum</option>
                      <option value="dokter_gigi">Dokter Gigi</option>
                      <option value="perawat">Perawat</option>
                      <option value="bidan">Bidan</option>
                      <option value="apoteker">Apoteker</option>
                      <option value="radiografer">Radiografer</option>
                      <option value="analis_lab">Analis Lab</option>
                      <option value="nakes_lainnya">Nakes Lainnya</option>
                    </select>
                  </div>
                  <div class="col">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control form-control-sm">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="meninggal">Meninggal</label>
                <input type="number" name="meninggal" id="meninggal" class="form-control form-control-sm">
              </div>
              <div class="col">
                <label for="dirawat">Dirawat</label>
                <input type="number" name="dirawat" id="dirawat" class="form-control form-control-sm">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="isoman">Isoman</label>
                <input type="number" name="isoman" id="isoman" class="form-control form-control-sm">
              </div>
              <div class="col">
                <label for="sembuh">Sembuh</label>
                <input type="number" name="sembuh" id="sembuh" class="form-control form-control-sm">
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

    function clearSession() {
      sessionStorage.clear();
    }

    function init() {
      sessionStorage.setItem('co_ass', 0);
      sessionStorage.setItem('residen', 0);
      sessionStorage.setItem('intership', 0);
      sessionStorage.setItem('dokter_spesialis', 0);
      sessionStorage.setItem('dokter_umum', 0);
      sessionStorage.setItem('dokter_gigi', 0);
      sessionStorage.setItem('perawat', 0);
      sessionStorage.setItem('bidan', 0);
      sessionStorage.setItem('apoteker', 0);
      sessionStorage.setItem('radiografer', 0);
      sessionStorage.setItem('analis_lab', 0);
      sessionStorage.setItem('nakes_lainnya', 0);

      $('#paramedic > option').each(function() {
        keyMeninggal = $(this).val()+"_meninggal";
        keyDirawat = $(this).val()+"_dirawat";
        keyIsoman = $(this).val()+"_isoman";
        keySembuh = $(this).val()+"_sembuh";
        sessionStorage.setItem(keyMeninggal, 0);
        sessionStorage.setItem(keyDirawat, 0);
        sessionStorage.setItem(keyIsoman, 0);
        sessionStorage.setItem(keySembuh, 0);
      })
    }

    function setSession(key, value) {
      sessionStorage.setItem(key, value);
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
      paramedic = $('#paramedic').val();
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
                url: "{{ route('nakesterinfeksi.store') }}",
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
        paramedic = $(this).val();
      });
      $('#jumlah').change(function() {
        var jumlah = $(this).val();
        var key = paramedic;
        setSession(key, jumlah);
      });
      $('#meninggal').change(function() {
        var meninggal = $(this).val();
        var key = paramedic+"_meninggal";
        setSession(key, meninggal);
      });
      $('#dirawat').change(function() {
        var dirawat = $(this).val();
        var key = paramedic+"_dirawat";
        setSession(key, dirawat);
      });
      $('#isoman').change(function() {
        var isoman = $(this).val();
        var key = paramedic+"_isoman";
        setSession(key, isoman);
      });
      $('#sembuh').change(function() {
        var sembuh = $(this).val();
        var key = paramedic+"_sembuh";
        setSession(key, sembuh);
      });

      $('#paramedic').keydown(function(e) {
        if(e.which == 9) {
          $('#jumlah').val("");
          $('#jumlah').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#sembuh').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
      $('#jumlah').keydown(function(e) {
        if(e.which == 9) {
          $('#meninggal').val("");
          $('#meninggal').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#paramedic').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
      $('#meninggal').keydown(function(e) {
        if(e.which == 9) {
          $('#dirawat').val("");
          $('#dirawat').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#jumlah').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
      $('#dirawat').keydown(function(e) {
        if(e.which == 9) {
          $('#isoman').val("");
          $('#isoman').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#meninggal').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
      $('#isoman').keydown(function(e) {
        if(e.which == 9) {
          $('#sembuh').val("");
          $('#sembuh').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#dirawat').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
      $('#sembuh').keydown(function(e) {
        if(e.which == 9) {
          $('#paramedic').focus();
          e.preventDefault();
        }
        if(e.which == 37) {
          $('#isoman').focus();
          e.preventDefault();
        }
        enterKey(e);
      });
    });
  </script>
@endpush