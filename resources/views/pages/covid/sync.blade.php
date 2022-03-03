@extends('layouts.app', [
  'class' => '',
  'elementActive' => 'covid'
])

@section('content')
  <div class="content">
    <div id="app">
      @if ($errorApi)
      <alert-api></alert-api>
      @endif
      <div class="card card-nav-tabs card-plain shadow">
        <div class="card-header">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                  <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item">
                        <a class="nav-covid nav-link" href="#covid" data-toggle="tab">Data Covid</a>
                      </li>
                      @if ($kirim)
                      <li class="nav-item">
                        <a class="nav-diagnosa nav-link" href="#diagnosa" data-toggle="tab">Diagnosa</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-komorbid nav-link" href="#komorbid" data-toggle="tab">Komorbid</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-terapi nav-link" href="#terapi" data-toggle="tab">Terapi</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-vaksinasi nav-link" href="#vaksinasi" data-toggle="tab">Vaksinasi</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-statuskeluar nav-link" href="#statuskeluar" data-toggle="tab">Status Keluar</a>
                      </li>
                      @endif
                      <li class="nav-item">
                        <a class="nav-penjelasan nav-link" href="#penjelasan" data-toggle="tab">Penjelasan</a>
                      </li>
                  </ul>
              </div>
            </div>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-covid tab-pane" id="covid">
              <form name="form-covid" id="form-covid">
                <a href="{{ route('covid.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>
                
                <button type="button" id="simpan-covid" class="simpan-covid btn btn-sm btn-primary">{{ $tombol }} <i class="fas fa-save"></i></button>
                @if (auth()->user()->role_id == 1)
                <button type="button" id="kirim-covid" class="kirim-covid btn btn-sm btn-success" {{ $errorApi ? 'disabled': '' }}>{{ $btnKirim }} <i class="fas fa-paper-plane"></i></button>
                @endif

                <div class="btn btn-sm btn-{{ $pesan['warna'] }} float-right"><i class="fas fa-exclamation"></i> Status: {{ $pesan['pesan'] }}</div>
                
                <div class="row">
                  <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                      <label for="kewarganegaraanId">Kewarganegaraan</label>
                      <input type="text" name="kewarganegaraanId" id="kewarganegaraanId" value="{{ $data['kewarganegaraanId'] }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                      <label for="nik">NIK</label>
                      <input type="text" name="nik" id="nik" value="{{ $data['nik'] }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                      <label for="noPassport">Passport</label>
                      <input type="text" name="noPassport" id="noPassport" value="{{ $data['noPassport'] }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                      <label for="noRM">No RM</label>
                      <input type="text" name="noRM" id="noRM" value="{{ $data['noRM'] }}" class="form-control form-control-sm" {{ $readonly }}>
                    </div>
                    <div class="form-group">
                      <label for="namaLengkapPasien">Nama Lengkap Pasien</label>
                      <input type="text" name="namaLengkapPasien" id="namaLengkapPasien" value="{{ $data['namaLengkapPasien'] }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                      <label for="namaInisialPasien">Nama Inisial Pasien</label>
                      <input type="text" name="namaInisialPasien" id="namaInisialPasien" value="{{ $data['namaInisialPasien'] }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                      <label for="tanggalLahir">Tanggal Lahir</label>
                      <input type="date" name="tanggalLahir" id="tanggalLahir" value="{{ $data['tanggalLahir'] }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" name="email" id="email" value="{{ $data['email'] }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                      <label for="noTelp">No Telepon/HP</label>
                      <input type="text" name="noTelp" id="noTelp" value="{{ $data['noTelp'] }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                      <label for="jenisKelaminId">Jenis Kelamin</label>
                      <select name="jenisKelaminId" id="jenisKelaminId" class="form-control">
                        <option value="L" {{ $data['jenisKelaminId'] == "L" ? 'selected': '' }}>Laki-Laki</option>
                        <option value="P" {{ $data['jenisKelaminId'] == "P" ? 'selected': '' }}>Perempuan</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                      <label for="asalPasienId">Asal Pasien</label>
                      <select type="text" name="asalPasienId" id="asalPasienId" class="form-control">
                        @if ($data['asalPasienApi']->message == 'Forbidden')
                          <option>{{ $data['asalPasienApi']->message }}</option>
                        @elseif ($data['asalPasienApi']->status == false)
                          <option>{{ $data['asalPasienApi']->message }}</option>
                        @else
                          @foreach ($data['asalPasienApi'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data['asalPasienId'] ? 'selected': '' }}>{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="domisiliProvinsiId">- Provinsi</label>
                      <select name="domisiliProvinsiId" id="domisiliProvinsiId" class="form-control">
                        @if(isset($data['provinsiApi']->message))
                          @if ($data['provinsiApi']->message == 'Forbidden')
                            <option>{{ $data['provinsiApi']->message }}</option>
                          @elseif ($data['provinsiApi']->status == false)
                            <option>{{ $data['provinsiApi']->message }}</option>
                          @endif
                        @else
                          <option value="-1">-- PILIH --</option>
                          @foreach ($data['provinsiApi'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data['domisiliProvinsiId'] ? 'selected': '' }}>{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="domisiliKabKotaId">- Kab/Kota</label>
                      <select name="domisiliKabKotaId" id="domisiliKabKotaId" class="form-control">
                        @if (isset($data['kabKotaApi']->message))
                          @if ($data['kabKotaApi']->message == 'Forbidden')
                            <option>{{ $data['kabKotaApi']->message }}</option>
                          @elseif ($data['kabKotaApi']->status == false)
                            <option>{{ $data['kabKotaApi']->message }}</option>
                          @endif
                        @else
                          <option value="-1">-- PILIH --</option>
                          @foreach ($data['kabKotaApi'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data['domisiliKabKotaId'] ? 'selected': '' }}>{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="domisiliKecamatanId">- Kecamatan</label>
                      <select name="domisiliKecamatanId" id="domisiliKecamatanId" class="form-control">
                        @if ($data['kecamatanApi']->status == false)
                          <option>{{ $data['kecamatanApi']->message }}</option>
                        @else
                          <option value="-1">-- PILIH --</option>
                          @foreach ($data['kecamatanApi'] as $list)
                            @foreach ($list as $item)
                              <option value="{{ $item->id }}" {{ $item->id == $data['domisiliKecamatanId'] ? 'selected': '' }}>{{ $item->nama }}</option>
                            @endforeach
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="pekerjaanId">Pekerjaan</label>
                      <select name="pekerjaanId" id="pekerjaanId" class="form-control">
                        @if ($data['pekerjaanApi']->status == false)
                          <option>{{ $data['pekerjaanApi']->message }}</option>
                        @else
                          @foreach ($data['pekerjaanApi'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data['pekerjaanId'] ? 'selected': '' }}>{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="tanggalMasuk">Tanggal Masuk/Registration</label>
                      <input type="date" name="tanggalMasuk" id="tanggalMasuk" value="{{ $data['tanggalMasuk'] }}" class="form-control" {{ $readonly }}>
                    </div>
                    <div class="form-group">
                      <label for="jenisPasienId">Jenis Pasien</label>
                      <select name="jenisPasienId" id="jenisPasienId" class="form-control">
                        @if ($data['jenisPasienApi']->status == false)
                          <option>{{ $data['jenisPasienApi']->message }}</option>
                        @else
                          @foreach ($data['jenisPasienApi'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data['jenisPasienId'] ? 'selected': '' }}>{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="statusPasienId">Status Pasien</label>
                      <select name="statusPasienId" id="statusPasienId" class="form-control">
                        @if ($data['statusPasienApi']->status == false)
                          <option>{{ $data['statusPasienApi']->message }}</option>
                        @else
                          @foreach ($data['statusPasienApi'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data['statusPasienId'] ? 'selected': '' }}>{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="statusCoInsidenId">Status CO Insiden</label>
                      <select name="statusCoInsidenId" id="statusCoInsidenId" class="form-control">
                        <option value="0" {{ $data['statusCoInsidenId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['statusCoInsidenId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="statusRawatId">Status Rawat</label>
                      <select name="statusRawatId" id="statusRawatId" class="form-control">
                        @if ($data['statusRawatId']->status == false)
                          <option>{{ $data['statusRawatId']->message }}</option>
                        @else
                          @foreach ($data['statusRawatId'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == 32 ? 'selected': '' }}>{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                      <label for="alatOksigenId">Alat Oksigen</label>
                      <select name="alatOksigenId" id="alatOksigenId" class="form-control">
                        @if ($data['alatOksigenId']->status == false)
                          <option>{{ $data['alatOksigenId']->message }}</option>
                        @else
                          <option value="-1">-- PILIH --</option>
                          @foreach ($data['alatOksigenId'] as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="penyintasId">Penyintas</label>
                      <select name="penyintasId" id="penyintasId" class="form-control">
                        <option value="0">Tidak</option>
                        <option value="1">Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="tanggalOnsetGejala">Tanggal Onset Gejala</label>
                      <input type="date" name="tanggalOnsetGejala" id="tanggalOnsetGejala" value="{{ $data['tanggalOnsetGejala'] }}" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="kelompokGejalaId">Kelompok Gejala</label>
                      <select name="kelompokGejalaId" id="kelompokGejalaId" class="form-control">
                        @if ($data['kelompokGejalaApi']->status == false)
                          <option>{{ $data['kelompokGejalaApi']->message }}</option>
                        @else
                          @foreach ($data['kelompokGejalaApi'] as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data['kelompokGejalaId'] ? 'selected': '' }}>{{ $item->nama }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="demamId">Demam</label>
                      <select name="demamId" id="demamId" class="form-control">
                        <option value="0" {{ $data['gejala']['demamId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['demamId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="batukId">Batuk</label>
                      <select name="batukId" id="batukId" class="form-control">
                        <option value="0" {{ $data['gejala']['batukId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['batukId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="pilekId">Pilek</label>
                      <select name="pilekId" id="pilekId" class="form-control">
                        <option value="0" {{ $data['gejala']['pilekId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['pilekId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="sakitTenggorokanId">Sakit Tenggorokan</label>
                      <select name="sakitTenggorokanId" id="sakitTenggorokanId" class="form-control">
                        <option value="0" {{ $data['gejala']['sakitTenggorokanId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['sakitTenggorokanId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="sesakNapasId">Sesak Napas</label>
                      <select name="sesakNapasId" id="sesakNapasId" class="form-control">
                        <option value="0" {{ $data['gejala']['sesakNapasId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['sesakNapasId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="lemasId">Lemas</label>
                      <select name="lemasId" id="lemasId" class="form-control">
                        <option value="0" {{ $data['gejala']['lemasId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['lemasId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                      <label for="nyeriOtotId">Nyeri Otot</label>
                      <select name="nyeriOtotId" id="nyeriOtotId" class="form-control">
                        <option value="0" {{ $data['gejala']['nyeriOtotId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['nyeriOtotId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="mualMuntahId">Mual Muntah</label>
                      <select name="mualMuntahId" id="mualMuntahId" class="form-control">
                        <option value="0" {{ $data['gejala']['mualMuntahId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['mualMuntahId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="diareId">Diare</label>
                      <select name="diareId" id="diareId" class="form-control">
                        <option value="0" {{ $data['gejala']['diareId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['diareId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="anosmiaId">Anosmia</label>
                      <select name="anosmiaId" id="anosmiaId" class="form-control">
                        <option value="0" {{ $data['gejala']['anosmiaId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['anosmiaId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="napasCepatId">Napas Cepat</label>
                      <select name="napasCepatId" id="napasCepatId" class="form-control">
                        <option value="0" {{ $data['gejala']['napasCepatId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['napasCepatId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="frekNapas30KaliPerMenitId">Frek Napas 30 Kali/Menit</label>
                      <select name="frekNapas30KaliPerMenitId" id="frekNapas30KaliPerMenitId" class="form-control">
                        <option value="0" {{ $data['gejala']['frekNapas30KaliPerMenitId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['frekNapas30KaliPerMenitId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="distresPernapasanBeratId">Distres Pernapasan Berat</label>
                      <select name="distresPernapasanBeratId" id="distresPernapasanBeratId" class="form-control">
                        <option value="0" {{ $data['gejala']['distresPernapasanBeratId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['distresPernapasanBeratId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="lainnyaId">Lainnya</label>
                      <select name="lainnyaId" id="lainnyaId" class="form-control">
                        <option value="0" {{ $data['gejala']['lainnyaId'] == 0 ? 'selected': '' }}>Tidak</option>
                        <option value="1" {{ $data['gejala']['lainnyaId'] == 1 ? 'selected': '' }}>Ya</option>
                      </select>
                    </div>
                  </div>
                </div>            
              </form>
            </div>
            @if ($kirim)    
            <div class="tab-diagnosa tab-pane" id="diagnosa">
              <form name="form-diagnosa" id="form-diagnosa">
                <a href="{{ route('covid.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>
                <button type="button" id="simpan-diagnosa" class="simpan-diagnosa btn btn-sm btn-primary">{{ $tombolDiagnosa }} <i class="fas fa-save"></i></button>
                {{-- <button type="button" id="kirim-diagnosa" class="kirim-diagnosa btn btn-sm btn-success" {{ $errorApi ? 'disabled': '' }}>{{ $btnKirimDiagnosa }} <i class="fas fa-paper-plane"></i></button> --}}
                @if (auth()->user()->role_id == 1)
                <button type="button" id="kirim-diagnosa" class="kirim-diagnosa btn btn-sm btn-success">{{ $btnKirimDiagnosa }} <i class="fas fa-paper-plane"></i></button>
                @endif
                {{-- <div class="btn btn-sm btn-{{ $pesanDiagnosa['warna'] }} float-right"><i class="fas fa-exclamation"></i> Status: {{ $pesanDiagnosa['pesan'] }}</div> --}}

                <table id="dt-diagnosa" class="table table-sm table-bordered" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Status</th>
                      <th>Diagnosa Code</th>
                      <th>Level</th>
                      <th>Onset Date</th>
                      <th>Diagnosis</th>
                      <th>ICD Block</th>
                      <th>ICD Name</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </form>
            </div>
            <div class="tab-komorbid tab-pane" id="komorbid">
              <form name="form-komorbid" id="form-komorbid">
                <a href="{{ route('covid.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>
                <button type="button" id="simpan-komorbid" class="simpan-komorbid btn btn-sm btn-primary">{{ $tombolKomorbid }} <i class="fas fa-save"></i></button>
                @if (auth()->user()->role_id == 1)
                <button type="button" id="kirim-komorbid" class="kirim-komorbid btn btn-sm btn-success">{{ $btnKirimKomorbid }} <i class="fas fa-paper-plane"></i></button>
                @endif

                <table id="dt-komorbid" class="table table-sm table-bordered" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Status</th>
                      <th>Komorbid</th>
                      <th>Onset Date</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </form>
            </div>
            <div class="tab-terapi tab-pane" id="terapi">
              <form name="form-terapi" id="form-terapi">
                <a href="{{ route('covid.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>
                {{-- <button type="button" id="simpan-terapi" class="simpan-terapi btn btn-sm btn-primary">{{ $tombolTerapi }} <i class="fas fa-save"></i></button> --}}
                @if (auth()->user()->role_id == 1)
                <button type="button" id="kirim-terapi" class="kirim-terapi btn btn-sm btn-success">{{ $btnKirimTerapi }} <i class="fas fa-paper-plane"></i></button>
                @endif
                <button type="button" class="tambah-terapi btn btn-sm btn-round btn-icon btn-success" id="tambah-terapi"><i class="fas fa-plus"></i></button>
                
                <div class="row mb-2">
                  <div class="col-sm-3 col-md-3">
                    <select class="float-right form-control form-control-sm" name="terapi-covid" id="terapi-covid"></select>
                  </div>
                  <div class="col-sm-3 col-md-3">
                    <input class="form-control form-control-sm" type="number" name="jumlahTerapi" id="jumlahTerapi" placeholder="Jumlah Terapi">
                  </div>
                </div>

                <table id="dt-terapi" class="table table-sm table-bordered" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Status</th>
                      <th>Terapi</th>
                      <th>Jumlah</th>
                      <th>Onset Date</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </form>
            </div>
            <div class="tab-vaksinasi tab-pane" id="vaksinasi">
              <form name="form-vaksinasi" id="form-vaksinasi">
                <a href="{{ route('covid.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>
                {{-- <button type="button" id="simpan-terapi" class="simpan-terapi btn btn-sm btn-primary">{{ $tombolTerapi }} <i class="fas fa-save"></i></button> --}}
                @if (auth()->user()->role_id == 1)
                <button type="button" id="kirim-vaksinasi" class="kirim-vaksinasi btn btn-sm btn-success">{{ $btnKirimVaksinasi }} <i class="fas fa-paper-plane"></i></button>
                @endif
                <button type="button" class="tambah-vaksinasi btn btn-sm btn-round btn-icon btn-success" id="tambah-vaksinasi"><i class="fas fa-plus"></i></button>

                <div class="row mb-2">
                  <div class="col-sm-3 col-md-3">
                    <select class="form-control form-control-sm" name="dosis-vaksin" id="dosis-vaksin"></select>
                  </div>
                  <div class="col-sm-3 col-md-3">
                    <select class="form-control form-control-sm" name="jenis-vaksin" id="jenis-vaksin"></select>
                  </div>
                </div>

                <table id="dt-vaksinasi" class="table table-sm table-bordered" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Status</th>
                      <th>ID</th>
                      <th>Doksis Vaksin</th>
                      <th>Jenis Vaksin</th>
                      <th>Onset Date</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </form>
            </div>
            <div class="tab-statuskeluar tab-pane" id="statuskeluar">
              <form name="form-statuskeluar" id="form-statuskeluar">
                <a href="{{ route('covid.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>
                {{-- <button type="button" id="simpan-terapi" class="simpan-terapi btn btn-sm btn-primary">{{ $tombolTerapi }} <i class="fas fa-save"></i></button> --}}
                <button type="button" id="kirim-statuskeluar" class="kirim-statuskeluar btn btn-sm btn-success">Kirim <i class="fas fa-paper-plane"></i></button>
                {{-- <button type="button" class="tambah-statuskeluar btn btn-sm btn-round btn-icon btn-success" id="tambah-statuskeluar"><i class="fas fa-plus"></i></button> --}}

                <div class="card shadow-none">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="tanggalKeluar">Tanggal Keluar</label>
                          <input type="date" name="tanggalKeluar" id="tanggalKeluar" class="form-control" value="{{ \Carbon\Carbon::parse($registration->DischargeDateTime)->format('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                          <label for="statusKeluarId">Status Keluar</label>
                          <select name="statusKeluarId" id="statusKeluarId" class="form-control">
                            <option value="-1">-- PILIH --</option>
                            <option value="4" {{ $statusKeluar == 4 ? 'selected': '' }}>APD / Isolasi mandiri</option>
                            <option value="5" {{ $statusKeluar == 5 ? 'selected': '' }}>APS</option>
                            <option value="0" {{ $statusKeluar == 0 ? 'selected': '' }}>Dirawat</option>
                            <option value="3" {{ $statusKeluar == 3 ? 'selected': '' }}>Dirujuk</option>
                            <option value="6" {{ $statusKeluar == 6 ? 'selected': '' }}>Discarded</option>
                            <option value="2" {{ $statusKeluar == 2 ? 'selected': '' }}>Meninggal</option>
                            <option value="1" {{ $statusKeluar == 1 ? 'selected': '' }}>Sembuh</option>              
                          </select>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-group">
                          <label for="kasusKematianId">Kasus Kematian</label>
                          <select name="kasusKematianId" id="kasusKematianId" class="form-control">
                              <option value="-1">-- PILIH --</option>
                              <option value="1">Discarded / Negatif</option>
                              <option value="2">Probable / Hasil Lab. tidak diketahui</option>
                              <option value="3">Konfirmasi</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="penyebabKematianLangsungId">Penyebab Kematian Langsung</label>
                          <select name="penyebabKematianLangsungId" id="penyebabKematianLangsungId" class="form-control">
                              <option value="-1">-- PILIH --</option>
                              <option value="J80">ARDS (Acute Respiratory Distress Syndrom)</option>
                              <option value="R65.3">MOF (Multi Organ Failure)- Non infectious</option>
                              <option value="R65.1">MOF (Multi Organ Failure)- Infectious</option>
                              <option value="R57.2">Septic Shock</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            @endif
            <div class="tab-penjelasan tab-pane" id="penjelasan">
              
            </div>
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
  @include('pages.covid.scripts.nav_state')
  <script type="text/javascript">
    $(document).ready(function () {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    });

    $(document).ready(function () {
      var countRecords = {{ $countPatient }};
      var countKomorbid = {{ $countKomorbid }};
      var countTerapi = {{ $countTerapi }};
      var countVaksinasi = {{ $countVaksinasi }};

      var table = $('#dt-diagnosa').DataTable({
        select: {
          style: 'multi'
        },
        // stateSave: true,
        // rowId: 'OnsetDate',
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
          url: "{{ route('diagnosis.dt-diagnosis') }}",
          data: {
            "noRM": "{{ $medicalno }}",
            "registrationno": "{{ $registrationno }}"
          }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'DiagnosisCode', name: 'DiagnosisCode'},
            {data: 'Level', name: 'Level'},
            {data: 'OnsetDate', name: 'OnsetDate'},
            {data: 'DiagnosisName', name: 'DiagnosisName'},
            {data: 'ICDBlockID', name: 'ICDBlockID'},
            {data: 'ICDBlockName', name: 'ICDBlockName'}
        ],
      });

      var tableKomorbid = $('#dt-komorbid').DataTable({
        select: {
          style: 'multi',
        },
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
          url: "{{ route('komorbid.dt-komorbid') }}",
          data: {
            "noRM": "{{ $medicalno }}",
            "registrationno": "{{ $registrationno }}"
          }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'DiagnosisCode', name: 'DiagnosisCode'},
            {data: 'OnsetDate', name: 'OnsetDate'}
        ],
      });

      var tableTerapi = $('#dt-terapi').DataTable({
        select: {
          style: 'multi',
        },
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
          url: "{{ route('terapi.dt-terapi') }}",
          data: {
            "noRM": "{{ $medicalno }}",
            "registrationno": "{{ $registrationno }}",
            "id_laporan": "{{ $covid == null ? null: $covid->id_laporan }}"
          }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'terapiId', name: 'terapiId'},
            {data: 'jumlahTerapi', name: 'jumlahTerapi'},
            {data: 'OnsetDate', name: 'OnsetDate'}
        ],
      });

      var tableVaksinasi = $('#dt-vaksinasi').DataTable({
        select: {
          style: 'multi',
        },
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
          url: "{{ route('vaksinasi.dt-vaksinasi') }}",
          data: {
            "noRM": "{{ $medicalno }}",
            "registrationno": "{{ $registrationno }}",
            "id_laporan": "{{ $covid == null ? null: $covid->id_laporan }}"
          }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},
            {data: 'id', name: 'id'},
            {data: 'dosisVaksinId', name: 'dosisVaksinId'},
            {data: 'jenisVaksinId', name: 'jenisVaksinId'},
            {data: 'OnsetDate', name: 'OnsetDate'}
        ],
      });

      $('#kasusKematianId').prop('disabled', true);
      $('#penyebabKematianLangsungId').prop('disabled', true);
      $('#statusKeluarId').change(function() {
        if($(this).val() == 2) {
          $('#kasusKematianId').prop('disabled', false);
          $('#penyebabKematianLangsungId').prop('disabled', false);
        } else {
          $('#kasusKematianId').prop('disabled', true);
          $('#penyebabKematianLangsungId').prop('disabled', true);
          $(`#kasusKematianId option[value=-1]`).prop('selected', true);
          $(`#penyebabKematianLangsungId option[value=-1]`).prop('selected', true);
        }
      });

      $('#terapi-covid').select2({
        placeholder: "-- PILIH OBAT --",
        ajax: {
          url: "{{ route('terapi.select-dataobat') }}",
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

      $('#dosis-vaksin').select2({
        placeholder: "-- DOSIS VAKSIN --",
        ajax: {
          url: "{{ route('vaksinasi.select-dosisvaksin') }}",
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

      $('#jenis-vaksin').select2({
        placeholder: "-- JENIS VAKSIN --",
        ajax: {
          url: "{{ route('vaksinasi.select-jenisvaksin') }}",
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

      $('.tambah-terapi').click(function() {
        var selectTerapi = $('#terapi-covid').val();
        var jumlahTerapi = $('#jumlahTerapi').val();
        console.log(selectTerapi);
        $.ajax({
          data: { 
            "id_laporan": "{{ $covid ? $covid->id_laporan: ""}}",
            "registration": "{{ $registration->RegistrationDateTime }}",
            "req": selectTerapi,
            "jumlah_terapi": jumlahTerapi,
          },
          url: "{{ route('terapi.tambah-terapi') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            var dtTerapi = $('#dt-terapi').dataTable();
            if(data.icon == 'success') {
              Swal.fire({
                position: 'center',
                icon: data.icon,
                title: data.message,
                showConfirmButton: false,
                timer: 1500
              });
              dtTerapi.fnDraw(false);
            }
          }
        });
      });

      $('.tambah-vaksinasi').click(function() {
        var dosisVaksin = $('#dosis-vaksin').val();
        var jenisVaksin = $('#jenis-vaksin').val();
        $.ajax({
          data: { 
            "id_laporan": "{{ $covid ? $covid->id_laporan: "" }}",
            "registration": "{{ $registration->RegistrationDateTime }}",
            "dosisVaksinId": dosisVaksin,
            "jenisVaksinId": jenisVaksin,
          },
          url: "{{ route('vaksinasi.tambah-vaksinasi') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            var dtTable = $('#dt-vaksinasi').dataTable();
            if(data.icon == 'success') {
              Swal.fire({
                position: 'center',
                icon: data.icon,
                title: data.message,
                showConfirmButton: false,
                timer: 1500
              });
              dtTable.fnDraw(false);
            }
          }
        });
      });

      $('#kelompokGejalaId').change(function() {
        var id = $(this).val();
        if(id == 1) {
          $(`#alatOksigenId option[value=-1]`).prop('selected',true);
        }
      });

      $('#alatOksigenId').change(function() {
        var id = $(this).val();
        if(id != -1) {
          $(`#kelompokGejalaId option[value=2]`).prop('selected',true);
        }
      });

      var data = [];
      data.length = countRecords;
      table.on('select', function ( e, dt, type, indexes ) {
        var rowData = table.rows( indexes ).data().toArray();
        data[indexes] = rowData;
      }).on('deselect', function ( e, dt, type, indexes ) {
        var rowData = table.rows( indexes ).data().toArray();
        data[indexes] = null;
      });

      var dataKomorbid = [];
      dataKomorbid.length = countKomorbid;
      tableKomorbid.on('select', function ( e, dt, type, indexes ) {
        var rowData = tableKomorbid.rows( indexes ).data().toArray();
        dataKomorbid[indexes] = rowData;
      }).on('deselect', function( e, dt, type, indexes ) {
        dataKomorbid[indexes] = null;
      });

      var dataTerapi = [];
      dataTerapi.length = countTerapi;
      tableTerapi.on('select', function ( e, dt, type, indexes ) {
        var rowData = tableTerapi.rows( indexes ).data().toArray();
        dataTerapi[indexes] = rowData;
      }).on('deselect', function( e, dt, type, indexes ) {
        dataTerapi[indexes] = null;
      });

      var dataVaksinasi = [];
      dataVaksinasi.length = countVaksinasi;
      tableVaksinasi.on('select', function ( e, dt, type, indexes ) {
        var rowData = tableVaksinasi.rows( indexes ).data().toArray();
        dataVaksinasi[indexes] = rowData;
      }).on('deselect', function( e, dt, type, indexes ) {
        dataVaksinasi[indexes] = null;
      });

      $('.kirim-statuskeluar').click(function() {
        var tombol = $(this).text();
        var req = dataVaksinasi.filter(Boolean);
        console.log(req);
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data Status Keluar?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Kirim',
          denyButtonText: 'Batal',
        }).then((result) => {
          if (result.isConfirmed) {
            $('.kirim-statuskeluar').prop('disabled', true);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              data: {
                "data": {
                  "tanggalKeluar": $('#tanggalKeluar').val(),
                  "statusKeluarId": $('#statusKeluarId').val(),
                  "kasusKematianId": $('#kasusKematianId').val(),
                  "penyebabKematianLangsungId": $('#penyebabKematianLangsungId').val(),
                },
                "covid": "{{ $covid ? $covid->id_laporan: "" }}"
              },
              url: "{{ route('statuskeluar.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.kirim-statuskeluar').prop('disabled', false);
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
              }
            });
          }
        })
      });

      $('.kirim-vaksinasi').click(function() {
        var tombol = $(this).text();
        var req = dataVaksinasi.filter(Boolean);
        console.log(req);
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data Vaksinasi?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Kirim',
          denyButtonText: 'Batal',
        }).then((result) => {
          if (result.isConfirmed) {
            $('.kirim-vaksinasi').prop('disabled', true);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              data: {
                "data": req,
                "covid": "{{ $covid ? $covid->id_laporan: "" }}"
              },
              url: "{{ route('vaksinasi.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.kirim-vaksinasi').prop('disabled', false);
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
              }
            });
          }
        })
      });

      $('.kirim-terapi').click(function() {
        var tombol = $(this).text();
        var req = dataTerapi.filter(Boolean);
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data Terapi?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Kirim',
          denyButtonText: 'Batal',
        }).then((result) => {
          if (result.isConfirmed) {
            $('.kirim-terapi').prop('disabled', true);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              data: {
                "data": req,
                "covid": "{{ $covid ? $covid->id_laporan: ""}}",
              },
              url: "{{ route('terapi.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.kirim-terapi').prop('disabled', false);
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
              }
            });
          }
        })
      });

      $('.simpan-komorbid').click(function() {
        var tombol = $(this).text();
        Swal.fire({
          title: 'Konfirmasi Penyimpanan Data Komorbid?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Simpan',
          denyButtonText: 'Batal',
        }).then((result) => {
          $('.simpan-komorbid').prop('disabled', true);
          if (result.isConfirmed) {
            var req = dataKomorbid.filter(Boolean);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              // contentType: 'application/json',
              data: {
                "data": req,
                "covid": "{{ $covid ? $covid->id_laporan: "" }}"
              },
              url: "{{ route('komorbid.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.simpan-komorbid').prop('disabled', false);
                if(data.icon == 'error') {
                  Swal.fire({
                    position: 'center',
                    icon: data.icon,
                    title: data.message,
                    showConfirmButton: true,
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
              }
            });
          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          }
        });
      });

      $('.kirim-komorbid').click(function() {
        var tombol = $(this).text();
        var req = dataKomorbid.filter(Boolean);
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data Komorbid?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Kirim',
          denyButtonText: 'Batal',
        }).then((result) => {
          if (result.isConfirmed) {
            $('.kirim-komorbid').prop('disabled', true);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              data: {
                "data": req,
                "covid": "{{ $covid == null ? "": $covid->id_laporan }}"
              },
              url: "{{ route('komorbid.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.kirim-komorbid').prop('disabled', false);
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
              }
            });
          }
        })
      });

      $('.simpan-diagnosa').click(function() {
        var tombol = $(this).text();
        Swal.fire({
          title: 'Konfirmasi Penyimpanan Data Diagnosa?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Simpan',
          denyButtonText: 'Batal',
        }).then((result) => {
          // $('.simpan-diagnosa').prop('disabled', true);
          if (result.isConfirmed) {
            console.log(data);
            var req = data.filter(Boolean);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              // contentType: 'application/json',
              data: {
                "data": req,
                "covid": "{{ $covid ? $covid->id_laporan: "" }}"
              },
              url: "{{ route('diagnosis.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.simpan-diagnosa').prop('disabled', false);
                if(data.icon == 'error') {
                  Swal.fire({
                    position: 'center',
                    icon: data.icon,
                    title: data.message,
                    showConfirmButton: true,
                  });
                } else {
                  Swal.fire({
                    position: 'center',
                    icon: data.icon,
                    title: data.message,
                    showConfirmButton: true,
                  }).then((result) => {
                    if(result.isConfirmed) {
                      if(data.message == 'Data disimpan!') {
                        location.reload();
                      }
                      if(data.simpan > 0 && data.update > 0) {
                        location.reload();
                      } else if (data.simpan > 0 || data.update > 0) {
                        location.reload();
                      }
                    }
                  });
                }
              }
            });
          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          }
        });
      });

      $('.kirim-diagnosa').click(function() {
        var tombol = $(this).text();
        var req = data.filter(Boolean);
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data Diagnosa?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Kirim',
          denyButtonText: 'Batal',
        }).then((result) => {
          if (result.isConfirmed) {
            $('.kirim-diagnosa').prop('disabled', true);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              data: {
                "data": req,
                "covid": "{{ $covid == null ? "": $covid->id_laporan }}"
              },
              url: "{{ route('diagnosis.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.kirim-diagnosa').prop('disabled', false);
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
              }
            });
          }
        })
      });

      $('.simpan-covid').click(function() {
        var tombol = $(this).text();
        Swal.fire({
          title: 'Konfirmasi Penyimpanan Data Covid?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Simpan',
          denyButtonText: 'Batal',
        }).then((result) => {
          if (result.isConfirmed) {
            $('.simpan-covid').prop('disabled', true);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              data: $('#form-covid').serialize(),
              url: "{{ route('covid.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.simpan-covid').prop('disabled', false);
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: data.message,
                  showConfirmButton: false,
                  timer: 1500
                });
                location.reload();
              },
              error: function (request, status, error) {
                Swal.fire({
                  position: 'center',
                  icon: 'error',
                  title: error,
                  showConfirmButton: true,
                });
                $('.simpan-covid').prop('disabled', false);
              }
            });
          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          }
        })
      });

      $('.kirim-covid').click(function() {
        var tombol = $(this).text();
        Swal.fire({
          title: 'Konfirmasi Pengiriman Data Covid?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Kirim',
          denyButtonText: 'Batal',
        }).then((result) => {
          if (result.isConfirmed) {
            $('.kirim-covid').prop('disabled', true);
            $.ajax({
              beforeSend: function(request) {
                request.setRequestHeader('tombol', tombol)
              },
              data: $('#form-covid').serialize(),
              url: "{{ route('covid.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('.kirim-covid').prop('disabled', false);
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
                    showConfirmButton: false,
                    timer: 1500
                  });
                  location.reload();
                }
              }
            });
          }
        })
      });
    });
  </script>
@endpush