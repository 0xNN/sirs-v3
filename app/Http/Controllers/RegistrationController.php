<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use App\Models\Diagnosa;
use App\Models\Komorbid;
use App\Models\Terapi;
use App\Models\Vaksinasi;
use App\Models\StatusKeluar;
use App\Models\Patient;
use App\Models\PatientProblem;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function registrationno($registrationno)
    {
        $registrationno = str_replace("-", '/', $registrationno);
        $reg = Registration::where('RegistrationNo', $registrationno)->first();
        if($reg == null) return JsonResponseController::jsonData(null, 'Data tidak ditemukan!');

        $data['RegistrationNo'] = $this->checkNull($reg->RegistrationNo);
        $data['RegistrationDateTime'] = $this->checkNull($reg->RegistrationDateTime);
        $data['MedicalNo'] = $this->checkNull($reg->MedicalNo);
        $data['ServiceUnitID'] = $this->checkNull($reg->ServiceUnitID);
        $data['ServiceUnitName'] = $this->checkNull($reg->departmentServiceUnit->serviceUnit->ServiceUnitName);
        $data['ParamedicID'] = $this->checkNull($reg->ParamedicID);
        $data['ParamedicName'] = $this->checkNull($reg->paramedic->ParamedicName);
        $data['RoomID'] = $this->checkNull($reg->RoomID);
        $data['RoomName'] = $this->checkNull($reg->serviceRoom->RoomName);
        $data['BedID'] = $this->checkNull($reg->BedID);
        $data['BedCode'] = $this->checkNull($reg->bed->BedCode);
        $data['ClassCode'] = $this->checkNull($reg->ClassCode);
        $data['ClassName'] = $this->checkNull($reg->classCode->ClassName);
        $data['BusinessPartnerID'] = $this->checkNull($reg->BusinessPartnerID);
        $data['BusinessPartnerName'] = $this->checkNull($reg->businessPartner->BusinessPartnerName);
        $data['PayerType'] = $this->checkNull($reg->PayerType);
        $data['GCPatientInType'] = $this->checkNull(GeneralCodeController::parsePatientType($reg->GCPatientInType));
        $data['DischargeDateTime'] = $this->checkNull($reg->DischargeDateTime);
        $data['IsDischarge'] = $this->isDischarge($reg->IsDischarge);
        $data['PresentIllnessNotes'] = $this->checkNull($reg->PresentIllnessNotes);
        $data['DischargeMedicalNotes'] = $this->checkNull($reg->DischargeMedicalNotes);
        $data['DischargeNotes'] = $this->checkNull($reg->DischargeNotes);

        return JsonResponseController::jsonData($data, 'Data ditemukan!');
    }

    public function checkNull($fill)
    {
        return $fill == null ? '-': $fill;
    }

    public function isDischarge($is)
    {
        return $is == 0 ? 'Tidak' : 'Ya';
    }

    public function parseKomorbid($data)
    {
        $arr = [];
        foreach($data as $item)
        {
            $exp = explode("-", $item[0]);
            array_push($arr, $exp[0]);
        }
        return $arr;
    }

    public function sync($registrationno, $medicalno)
    {
        $registrationno = str_replace("-", '/', $registrationno);
        $api = new ApiController;

        $errorApi = false;
        if($api->handleExpired() == 'page not found') {
            $errorApi = true;
        }

        $registration = Registration::where('RegistrationNo', $registrationno)->first();

        $statusKeluar = $this->parseDischarge($registration);

        // dd($statusKeluar);
        $covid = Covid::where('noRM', $medicalno)
                        ->where('tanggalMasuk', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
                        ->first();

        $diagnosa = null;
        $komorbid = null;
        $terapi = null;
        $vaksinasi = null;
        $statuskeluar = null;

        $countPatient = PatientProblem::where('MedicalNo', $medicalno)
            // ->where('OnsetDate', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
            ->orderByDesc('OnsetDate')
            ->count();

        $komorbid = $this->parseKomorbid($api->datakomorbidcovid());
        $countKomorbid = PatientProblem::where('MedicalNo', $medicalno)
            ->whereIn('DiagnosisCode', $komorbid)
            ->count();

        $countTerapi = 0;
        $countVaksinasi = 0;

        $pesan = $this->statusPesan($covid);
        $pesanDiagnosa = null;
        $pesanKomorbid = null;
        $pesanTerapi = null;
        $pesanVaksinasi = null;
        $pesanStatusKeluar = null;

        $tombol = $covid == null ? 'Simpan': 'Update';
        $tombolDiagnosa = null;
        $tombolKomorbid = null;
        $tombolTerapi = null;
        $tombolVaksinasi = null;
        $tombolStatusKeluar = null;

        $btnKirim = 'Kirim';
        $btnKirimDiagnosa = 'Kirim';
        $btnKirimKomorbid = 'Kirim';
        $btnKirimTerapi = 'Kirim';
        $btnKirimVaksinasi = 'Kirim';
        $btnKirimStatusKeluar = 'Kirim';

        $readonly = '';
        $kirim = false;

        if($covid) {
            // Aktifkan Tab Diagnosa, Terapi, dll
            if($covid->status_sinkron == 1) {
                $btnKirim = 'Kirim Ulang';
                $readonly = 'readonly';
                $kirim = true;

                // Diagnosa
                $diagnosa = Diagnosa::where('id_laporan', $covid->id_laporan)->first();
                $pesanDiagnosa = $this->statusPesan($diagnosa);
                $tombolDiagnosa = $diagnosa == null ? 'Simpan': 'Update';
                $btnKirimDiagnosa = 'Kirim';

                if($diagnosa) {
                    if($diagnosa->status_sinkron == 1) {
                        $btnKirimDiagnosa = 'Kirim Ulang';
                    }
                }

                // Komorbid
                $komorbid = Komorbid::where('id_laporan', $covid->id_laporan)->first();
                $pesanKomorbid = $this->statusPesan($komorbid);
                $tombolKomorbid = $komorbid == null ? 'Simpan': 'Update';
                $btnKirimKomorbid = 'Kirim';

                if($komorbid) {
                    if($komorbid->status_sinkron == 1) {
                        $btnKirimKomorbid = 'Kirim Ulang';
                    }
                }

                // Terapi
                $terapi = Terapi::where('id_laporan', $covid->id_laporan)->first();
                $pesanTerapi = $this->statusPesan($terapi);
                $tombolTerapi = $terapi == null ? 'Simpan': 'Update';
                $btnKirimTerapi = 'Kirim';
                $countTerapi = Terapi::where('id_laporan', $covid->id_laporan)->count();
                if($terapi) {
                    if($terapi->status_sinkron == 1) {
                        $btnKirimTerapi = 'Kirim Ulang';
                    }
                }

                // Vaksinasi
                $vaksinasi = Vaksinasi::where('id_laporan', $covid->id_laporan)->first();
                $pesanVaksinasi = $this->statusPesan($vaksinasi);
                $tombolVaksinasi = $vaksinasi == null ? 'Simpan': 'Update';
                $btnKirimVaksinasi = 'Kirim';
                $countVaksinasi = Vaksinasi::where('id_laporan', $covid->id_laporan)->count();
                if($vaksinasi) {
                    if($vaksinasi->status_sinkron == 1) {
                        $btnKirimVaksinasi = 'Kirim Ulang';
                    }
                }

                // Status Keluar
                $statuskeluar = StatusKeluar::where('id_laporan', $covid->id_laporan)->first();
                $pesanStatusKeluar = $this->statusPesan($statuskeluar);
                $tombolStatusKeluar = $statuskeluar == null ? 'Simpan': 'Update';
                $btnKirimStatusKeluar = 'Kirim';

                if($statuskeluar) {
                    if($statuskeluar->status_sinkron == 1) {
                        $btnKirimStatusKeluar = 'Kirim Ulang';
                    }
                }
            }
        }


        $patient = Patient::where('MedicalNo', $medicalno)->first();
        $patientproblem = PatientProblem::where('MedicalNo', $medicalno)
                                        ->whereIn('DiagnosisCode', config('myconfig.code_covid'))
                                        ->first();

        $jenis_pasien = $this->jenispasien($registration->GCOriginOfPatientReg);

        $nationality = GeneralCodeController::parseNationality($patient->GCNationality);
        // dd($api->kewarganegaraan());
        $kewarganegaraan = $this->kewarganegaraan($api->kewarganegaraan(), $nationality);

        if($covid) {
            $data['kewarganegaraanId'] = $covid->kewarganegaraanId;
            $data['nik'] = $covid->nik;
            $data['noPassport'] = $covid->noPassport;
            $data['asalPasienApi'] = $api->asalpasien();
            $data['asalPasienId'] = $covid->asalPasienId;
            $data['noRM'] = $covid->noRM;
            $data['namaLengkapPasien'] = $covid->namaLengkapPasien;
            $data['namaInisialPasien'] = $covid->namaInisialPasien;
            $data['tanggalLahir'] = $covid->tanggalLahir;
            $data['email'] = $covid->email;
            $data['noTelp'] = $covid->noTelp;
            $data['jenisKelaminId'] = $covid->jenisKelaminId;
            $data['domisiliProvinsiId'] = $covid->domisiliProvinsiId;
            $data['provinsiApi'] = $api->provinsi();
            $data['domisiliKabKotaId'] = $covid->domisiliKabKotaId;
            $data['kabKotaApi'] = $api->kabkota();
            $data['domisiliKecamatanId'] = $covid->domisiliKecamatanId;
            $data['kecamatanApi'] = $api->kecamatan();
            $data['pekerjaanId'] = $covid->pekerjaanId;
            $data['pekerjaanApi'] = $api->pekerjaan();
            $data['tanggalMasuk'] = $covid->tanggalMasuk;
            $data['jenisPasienId'] = $covid->jenisPasienId;
            $data['jenisPasienApi'] = $api->jenispasien();
            $data['statusPasienId'] = $covid->statusPasienId;
            $data['statusPasienApi'] = $api->statuspasien();
            $data['statusCoInsidenId'] = $covid->statusCoInsidenId;
            $data['statusRawatId'] = $api->statusrawat();
            $data['statusRawatApi'] = $api->statusrawat();
            $data['alatOksigenId'] = $api->alatoksigen();
            $data['alatOksigenApi'] = $api->alatoksigen();
            $data['penyintasId'] = $covid->penyintasId;
            $data['tanggalOnsetGejala'] = $covid->tanggalOnsetGejala;
            $data['kelompokGejalaId'] = 1;
            $data['kelompokGejalaApi'] = $api->kelompokgejala();
            $data['gejala']['demamId'] = $covid->demamId;
            $data['gejala']['batukId'] = $covid->batukId;
            $data['gejala']['pilekId'] = $covid->pilekId;
            $data['gejala']['sakitTenggorokanId'] = $covid->sakitTenggorokanId;
            $data['gejala']['sesakNapasId'] = $covid->sesakNapasId;
            $data['gejala']['lemasId'] = $covid->lemasId;
            $data['gejala']['nyeriOtotId'] = $covid->nyeriOtotId;
            $data['gejala']['mualMuntahId'] = $covid->mualMuntahId;
            $data['gejala']['diareId'] = $covid->diareId;
            $data['gejala']['anosmiaId'] = $covid->anosmiaId;
            $data['gejala']['napasCepatId'] = $covid->napasCepatId;
            $data['gejala']['frekNapas30KaliPerMenitId'] = $covid->frekNapas30KaliPerMenitId;
            $data['gejala']['distresPernapasanBeratId'] = $covid->distresPernapasanBeratId;
            $data['gejala']['lainnyaId'] = $covid->lainnyaId;
        } else {
            $data['kewarganegaraanId'] = $kewarganegaraan;
            $data['nik'] = $patient->SSN;
            $data['noPassport'] = null;
            $data['asalPasienId'] = 1;
            $data['asalPasienApi'] = $api->asalpasien();
            $data['noRM'] = $patient->MedicalNo;
            $data['namaLengkapPasien'] = $patient->PatientName;
            $data['namaInisialPasien'] = $patient->FirstName;
            $data['tanggalLahir'] = $patient->DateOfBirth->format('Y-m-d');
            $data['email'] = $this->email($patient->address->Email1, $patient->address->Email2);
            $data['noTelp'] = $patient->MobilePhoneNo1;
            $data['jenisKelaminId'] = GeneralCodeController::parseJK(GeneralCodeController::parseSex($patient->GCSex));
            $data['domisiliProvinsiId'] = $this->provinsi($api->provinsi(), $patient->address->sysGeneralCodeProvinsi->GeneralCodeName1);
            $data['provinsiApi'] = $api->provinsi();
            $data['domisiliKabKotaId'] = $this->kabkota($api->kabkota(), $patient->address->zipCode);
            $data['kabKotaApi'] = $api->kabkota();
            $data['domisiliKecamatanId'] = $this->kecamatan($api->kecamatan(), $patient->address->zipCode, $data['domisiliKabKotaId']);
            $data['kecamatanApi'] = $api->kecamatan();
            $data['pekerjaanId'] = 1;
            $data['pekerjaanApi'] = $api->pekerjaan();
            $data['tanggalMasuk'] = Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d');
            $data['jenisPasienId'] = $jenis_pasien;
            $data['jenisPasienApi'] = $api->jenispasien();
            $data['statusPasienId'] = $this->statuspasien($patientproblem->DiagnosisCode);
            $data['statusPasienApi'] = $api->statuspasien();
            $data['statusCoInsidenId'] = 0;
            $data['statusRawatId'] = $api->statusrawat();
            $data['statusRawatApi'] = $api->statusrawat();
            $data['alatOksigenId'] = $api->alatoksigen();
            $data['alatOksigenApi'] = $api->alatoksigen();
            $data['penyintasId'] = 0;
            $data['tanggalOnsetGejala'] = $patientproblem == null ? '1970-01-01' : Carbon::parse($patientproblem->OnsetDate)->format('Y-m-d');
            $data['kelompokGejalaId'] = 1;
            $data['kelompokGejalaApi'] = $api->kelompokgejala();
            $data['gejala']['demamId'] = 0;
            $data['gejala']['batukId'] = 0;
            $data['gejala']['pilekId'] = 0;
            $data['gejala']['sakitTenggorokanId'] = 0;
            $data['gejala']['sesakNapasId'] = 0;
            $data['gejala']['lemasId'] = 0;
            $data['gejala']['nyeriOtotId'] = 0;
            $data['gejala']['mualMuntahId'] = 0;
            $data['gejala']['diareId'] = 0;
            $data['gejala']['anosmiaId'] = 0;
            $data['gejala']['napasCepatId'] = 0;
            $data['gejala']['frekNapas30KaliPerMenitId'] = 0;
            $data['gejala']['distresPernapasanBeratId'] = 0;
            $data['gejala']['lainnyaId'] = 0;
        }

        return view('pages.covid.sync', compact(
            'data',
            'registrationno',
            'medicalno',
            'registration',
            'pesan',
            'pesanDiagnosa',
            'pesanKomorbid',
            'pesanTerapi',
            'pesanVaksinasi',
            'pesanStatusKeluar',
            'tombol',
            'tombolDiagnosa',
            'tombolKomorbid',
            'tombolTerapi',
            'tombolVaksinasi',
            'tombolStatusKeluar',
            'btnKirim',
            'btnKirimDiagnosa',
            'btnKirimKomorbid',
            'btnKirimTerapi',
            'btnKirimVaksinasi',
            'btnKirimStatusKeluar',
            'readonly',
            'kirim',
            'countPatient',
            'countKomorbid',
            'countTerapi',
            'countVaksinasi',
            'covid',
            'errorApi',
            'statusKeluar'
        ));
        // return JsonResponseController::jsonData($data, 'Data ditemukan!');
    }

    public function statusPesan($table)
    {
        $pesan = '';
        $warna = 'default';

        if($table == null) {
            $pesan = 'Belum disimpan!';
            $warna = 'warning';
        } else {
            if($table->status_sinkron == 0) {
                $pesan = 'Data disimpan namun belum dikirim!';
                $warna = 'info';
            } else {
                $pesan = 'Data disimpan dan dikirim!';
                $warna = 'success';
            }
        }
        $data = [
            'pesan' => $pesan,
            'warna' => $warna
        ];
        return $data;
    }

    public function statuspasien($diagnosa)
    {
        $id = -1;
        if($diagnosa == 'Z03.8') {
            $id = 1;
        } else if($diagnosa == 'B34.2') {
            $id = 3;
        } else {
            $id = 1;
        }
        return $id;
    }

    public function kewarganegaraan($list, $country)
    {
        if(isset($list->message)) {
            if($list->message == "Forbidden") {
                return $list->message;
            }
            if($list->status == false) {
                return $list->message;
            }
        }
        $id_country = '';
        foreach($list as $data) {
            if(trim($data->nicename) == trim($country)) {
                $id_country = $data->id;
                break;
            }
        }
        return $id_country;
    }

    public function provinsi($list, $provinsi)
    {
        if(isset($list->message)) {
            if($list->message == 'Forbidden') {
                return $list;
            }
            if($list->status == false) {
                return $list;
            }
        }
        $id_provinsi = -1;
        foreach($list as $data) {
            if(trim($data->nama) == trim($provinsi)) {
                $id_provinsi = $data->id;
                break;
            }
        }
        return $id_provinsi;
    }

    public function kabkota($list, $kabkota) 
    {
        if($kabkota == null) return null;
        $kk = strtoupper($kabkota->County.' '.$kabkota->City);
        $id_kabkota = -1;
        foreach($list as $data) {
            if(trim($data->nama) == trim($kk)) {
                $id_kabkota = $data->id;
                break;
            }
        }
        return (int)$id_kabkota;
    }

    public function kecamatan($list, $kecamatan, $kabkota)
    {
        if($kecamatan == null) return null;
        $keca = strtoupper($kecamatan->District);
        $id_kec = -1;

        foreach($list as $data) {
            foreach($data as $kec) {
                if($kec->kab_kota_id == $kabkota && trim($kec->nama) == trim($keca)) {
                    $id_kec = $kec->id;
                    break;
                }
            }
        }
        return (int)$id_kec;
    }

    public function jenispasien($jenis)
    {
        $model = [
            'X0133^02' => 1, // rawat jalan
            'X0133^01' => 3, // rawat inap
            'X0133^03' => 2, // IGD
        ];
        return isset($model[$jenis]) ? $model[$jenis] : 2;
    }

    public function email($email1, $email2)
    {
        if($email1 != null) return $email1;
        if($email2 != null) return $email2;
        return null;
    }

    public function parseDischarge($data)
    {
        $model = [
            "X0033^001" => -1,
            "X0033^002" => 3,
            "X0033^003" => -1,
            "X0033^004" => -1,
            "X0033^005" => 3,
            "X0033^006" => -1,
            "X0033^007" => 2
        ];
        if(isset($model[$data->GCDischargeMethod])) {
            if(isset($model[$data->GCDischargeMethod]) == -1) {
                $model = [
                    "X0034^001" => 1,
                    "X0034^002" => -1,
                    "X0034^003" => -1,
                    "X0034^004" => -1,
                    "X0034^005" => -1,
                    "X0034^006" => 1,
                    "X0034^007" => -1,
                    "X0034^008" => 1,
                ];

                if(isset($model[$data->GCDischargeCondition])) {
                    if(isset($model[$data->GCDischargeCondition]) != -1) {
                        return isset($model[$data->GCDischargeCondition]);
                    }
                }
            } else {
                return $model[$data->GCDischargeMethod];
            }
        }
        if($data->IsDischarge == 1) {
            return 1;
        }
        return 0;
    }
}
