@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user'
])

@section('content')
<div class="content">
    <div id="app">
      <form name="form-user" id="form-user">
        <div class="card shadow">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" name="name" id="name" class="form-control" placeholder="Nama">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="role_id">Role</label>
                      <select class="form-control" name="role_id" id="role_id">
                        <option value="-1">-- PILIH LEVEL --</option>
                        <option value="1">Admin</option>
                        <option value="0">User</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="akses_id">Akses</label>
                      <select class="form-control" name="akses_id" id="akses_id">
                        <option value="-1">-- PILIH LEVEL --</option>
                        <option value="1">Covid</option>
                        <option value="0">Non Covid</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="email">Username</label>
                  <input type="text" name="email" id="email" class="form-control" placeholder="Username">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="password">Password</label>
                  <input name="password" type="password" id="password" class="form-control" placeholder="Password">
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="button" class="btn btn-sm btn-success" name="btn-simpan" id="btn-simpan">Simpan</button>
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
    $(document).ready(function () {
      $('#btn-simpan').click(function() {
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
              data: $('#form-user').serialize(),
              url: "{{ route('simpanuser') }}",
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
      });
    });
</script>
@endpush