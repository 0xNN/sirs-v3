<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use App\Models\Diagnosis;
use App\Models\PatientProblem;
use App\Models\Registration;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CovidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diagnosis = Diagnosis::whereIn('DiagnosisCode', config('myconfig.code_covid'))->get();

        if(request()->ajax()) {
            // $data = Registration::leftjoin('patientproblem', function($q) {
            //     $q->on('registration.MedicalNo', 'patientproblem.MedicalNo');
            // })
            // ->where('registration.IsDischarge', 0)
            $data = Registration::whereIn('RoomID', config('myconfig.room'))
            ->orderBy('registration.RegistrationDateTime')
            ->distinct('registration.RegistrationNo')
            ->limit(10)
            ->get();

            return datatables()
                ->of($data)
                ->addIndexColumn()
                ->editColumn('action', function($row) {
                    $reg = str_replace("/","-",$row->RegistrationNo);
                    $button = '<div class="btn-group btn-group-sm" role="group">';
                    // $button .= '<button data-toggle="tooltip" data-id="'.$row->MedicalNo.'" registration-no="'.$row->RegistrationNo.'" data-original-title="Get Sphaira" class="get-sphaira btn btn-primary btn-sm"><i class="fas fa-save"></i></button>';
                    $button .= '<a class="btn btn-sm btn-primary" href="'.route('registration.sync', ['registrationno' => $reg, 'medicalno' => $row->MedicalNo]).'"><i class="fas fa-save"></i></a>';
                    $button .= '</div>';

                    return $button;
                })
                ->editColumn('butuh_sinkron_ulang', function($row) {
                    return $this->cek_sinkron($row->MedicalNo, $row->RegistrationDateTime);
                })
                ->editColumn('RegistrationNo', function($row) {
                    return '<button data-toggle="tooltip" data-id="'.$row->RegistrationNo.'" class="registrationno btn btn-success btn-sm">'.$row->RegistrationNo.'</button>';
                })
                ->editColumn('MedicalNo', function($row) {
                    return '<button data-toggle="tooltip" data-id="'.$row->MedicalNo.'" class="medicalno btn btn-sm btn-primary btn-link">'.$row->MedicalNo.'</button>';
                })
                ->editColumn('ServiceUnitID', function($row) {
                    if($row->departmentServiceUnit == null) {
                        return '-';
                    } else {
                        return $this->return($row->departmentServiceUnit->serviceUnit);
                    }
                })
                ->editColumn('IsDischarge', function($row) {
                    if($row->IsDischarge == 1) {
                        return '<span class="badge badge-success">Ya</span>';
                    } else {
                        return '<span class="badge badge-danger">Tidak</span>';
                    }
                })
                ->editColumn('PatientName', function($row) {
                    return $this->return($row->patient);
                })
                ->editColumn('ClassCode', function($row) {
                    return $this->return($row->classCode);
                })
                ->editColumn('RoomID', function($row) {
                    return $this->return($row->serviceRoom);
                })
                ->escapeColumns([])
                ->make(true);
        }
        
        return view('pages.covid.index', compact(
            'diagnosis'
        ));
    }

    public function cek_sinkron($no_rm, $registration_date)
    {
        $covid = Covid::where('noRM', $no_rm)
                        ->where('tanggalMasuk', $registration_date)
                        ->first();
        if($covid != null) {
            if($covid->butuh_sinkron_ulang == 1) {
                return "<span class='badge badge-warning'>Kirim Ulang</span>";
            }
            return "<span class='badge badge-success'>Terkirim</span>";
        }
        return "<span class='badge badge-danger'>Belum disimpan</span>";
    }

    public function return($row)
    {
        if($row == null) {
            return '-';
        } else {
            foreach($this->fill() as $fill)
            {
                if(isset($row->$fill)) {
                    return $row->$fill;
                }
            }
        }
    }

    public function fill()
    {
        // Jika ada fill yang ingin dipanggil tambahkan disini
        return [
            'PatientName',
            'ClassName',
            'RoomName',
            'ServiceUnitName',
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $api = new ApiController;
        iF($request->hasHeader('tombol')) {
            if($request->header('tombol') == "Simpan") {
                $covid = Covid::create([
                    "kewarganegaraanId" => $request->kewarganegaraanId,
                    "nik" => $request->nik,
                    "noPassport" => $request->noPassport,
                    "noRM" => $request->noRM,
                    "namaLengkapPasien" => $request->namaLengkapPasien,
                    "namaInisialPasien" => $request->namaInisialPasien,
                    "tanggalLahir" => $request->tanggalLahir,
                    "email" => $request->email,
                    "noTelp" => $request->noTelp,
                    "jenisKelaminId" => $request->jenisKelaminId,
                    "asalPasienId" => $request->asalPasienId,
                    "domisiliProvinsiId" => $request->domisiliProvinsiId == -1 ? null: $request->domisiliProvinsiId,
                    "domisiliKabKotaId" => $request->domisiliKabKotaId == -1 ? null: $request->domisiliKabKotaId,
                    "domisiliKecamatanId" => $request->domisiliKecamatanId == -1 ? null: $request->domisiliKecamatanId,
                    "pekerjaanId" => $request->pekerjaanId,
                    "tanggalMasuk" => $request->tanggalMasuk,
                    "jenisPasienId" => $request->jenisPasienId,
                    "statusPasienId" => $request->statusPasienId,
                    "statusCoInsidenId" => $request->statusCoInsidenId,
                    "statusRawatId" => $request->statusRawatId,
                    "alatOksigenId" => $request->alatOksigenId == -1 ? null: $request->alatOksigenId,
                    "penyintasId" => $request->penyintasId,
                    "tanggalOnsetGejala" => $request->tanggalOnsetGejala,
                    "kelompokGejalaId" => $request->kelompokGejalaId,
                    "demamId" => $request->demamId,
                    "batukId" => $request->batukId,
                    "pilekId" => $request->pilekId,
                    "sakitTenggorokanId" => $request->sakitTenggorokanId,
                    "sesakNapasId" => $request->sesakNapasId,
                    "lemasId" => $request->lemasId,
                    "nyeriOtotId" => $request->nyeriOtotId,
                    "mualMuntahId" => $request->mualMuntahId,
                    "diareId" => $request->diareId,
                    "anosmiaId" => $request->anosmiaId,
                    "napasCepatId" => $request->napasCepatId,
                    "frekNapas30KaliPerMenitId" => $request->frekNapas30KaliPerMenitId,
                    "distresPernapasanBeratId" => $request->distresPernapasanBeratId,
                    "lainnyaId" => $request->lainnyaId,

                    "tanggal_ambil_data" => Carbon::now(),
                    "status_sinkron" => 0,
                    "tanggal_sinkron" => Carbon::now(),
                    "user_id" => auth()->user()->id
                ]);
                return JsonResponseController::jsonData($covid, 'Data disimpan!');
            } else if ($request->header('tombol') == "Update") {
                $covid = Covid::where('noRM', $request->noRM)
                ->where('tanggalMasuk', $request->tanggalMasuk)
                ->update([
                    "kewarganegaraanId" => $request->kewarganegaraanId,
                    "nik" => $request->nik,
                    "noPassport" => $request->noPassport,
                    "noRM" => $request->noRM,
                    "namaLengkapPasien" => $request->namaLengkapPasien,
                    "namaInisialPasien" => $request->namaInisialPasien,
                    "tanggalLahir" => $request->tanggalLahir,
                    "email" => $request->email,
                    "noTelp" => $request->noTelp,
                    "jenisKelaminId" => $request->jenisKelaminId,
                    "asalPasienId" => $request->asalPasienId,
                    "domisiliProvinsiId" => $request->domisiliProvinsiId == -1 ? null: $request->domisiliProvinsiId,
                    "domisiliKabKotaId" => $request->domisiliKabKotaId == -1 ? null: $request->domisiliKabKotaId,
                    "domisiliKecamatanId" => $request->domisiliKecamatanId == -1 ? null: $request->domisiliKecamatanId,
                    "pekerjaanId" => $request->pekerjaanId,
                    "tanggalMasuk" => $request->tanggalMasuk,
                    "jenisPasienId" => $request->jenisPasienId,
                    "statusPasienId" => $request->statusPasienId,
                    "statusCoInsidenId" => $request->statusCoInsidenId,
                    "statusRawatId" => $request->statusRawatId,
                    "alatOksigenId" => $request->alatOksigenId == -1 ? null: $request->alatOksigenId,
                    "penyintasId" => $request->penyintasId,
                    "tanggalOnsetGejala" => $request->tanggalOnsetGejala,
                    "kelompokGejalaId" => $request->kelompokGejalaId,
                    "demamId" => $request->demamId,
                    "batukId" => $request->batukId,
                    "pilekId" => $request->pilekId,
                    "sakitTenggorokanId" => $request->sakitTenggorokanId,
                    "sesakNapasId" => $request->sesakNapasId,
                    "lemasId" => $request->lemasId,
                    "nyeriOtotId" => $request->nyeriOtotId,
                    "mualMuntahId" => $request->mualMuntahId,
                    "diareId" => $request->diareId,
                    "anosmiaId" => $request->anosmiaId,
                    "napasCepatId" => $request->napasCepatId,
                    "frekNapas30KaliPerMenitId" => $request->frekNapas30KaliPerMenitId,
                    "distresPernapasanBeratId" => $request->distresPernapasanBeratId,
                    "lainnyaId" => $request->lainnyaId,
                    "tanggal_ambil_data" => Carbon::now(),
                ]);
                return JsonResponseController::jsonData($covid, 'Data diupdate!');
            } else if ($request->header('tombol') == "Kirim") {
                $covid = Covid::updateOrCreate([
                    'noRM' => $request->noRM,
                    'tanggalMasuk' => $request->tanggalMasuk
                ],[
                    "kewarganegaraanId" => $request->kewarganegaraanId,
                    "nik" => $request->nik,
                    "noPassport" => $request->noPassport,
                    "noRM" => $request->noRM,
                    "namaLengkapPasien" => $request->namaLengkapPasien,
                    "namaInisialPasien" => $request->namaInisialPasien,
                    "tanggalLahir" => $request->tanggalLahir,
                    "email" => $request->email,
                    "noTelp" => $request->noTelp,
                    "jenisKelaminId" => $request->jenisKelaminId,
                    "asalPasienId" => $request->asalPasienId,
                    "domisiliProvinsiId" => $request->domisiliProvinsiId == -1 ? null: $request->domisiliProvinsiId,
                    "domisiliKabKotaId" => $request->domisiliKabKotaId == -1 ? null: $request->domisiliKabKotaId,
                    "domisiliKecamatanId" => $request->domisiliKecamatanId == -1 ? null: $request->domisiliKecamatanId,
                    "pekerjaanId" => $request->pekerjaanId,
                    "tanggalMasuk" => $request->tanggalMasuk,
                    "jenisPasienId" => $request->jenisPasienId,
                    "statusPasienId" => $request->statusPasienId,
                    "statusCoInsidenId" => $request->statusCoInsidenId,
                    "statusRawatId" => $request->statusRawatId,
                    "alatOksigenId" => $request->alatOksigenId == -1 ? null: $request->alatOksigenId,
                    "penyintasId" => $request->penyintasId,
                    "tanggalOnsetGejala" => $request->tanggalOnsetGejala,
                    "kelompokGejalaId" => $request->kelompokGejalaId,
                    "demamId" => $request->demamId,
                    "batukId" => $request->batukId,
                    "pilekId" => $request->pilekId,
                    "sakitTenggorokanId" => $request->sakitTenggorokanId,
                    "sesakNapasId" => $request->sesakNapasId,
                    "lemasId" => $request->lemasId,
                    "nyeriOtotId" => $request->nyeriOtotId,
                    "mualMuntahId" => $request->mualMuntahId,
                    "diareId" => $request->diareId,
                    "anosmiaId" => $request->anosmiaId,
                    "napasCepatId" => $request->napasCepatId,
                    "frekNapas30KaliPerMenitId" => $request->frekNapas30KaliPerMenitId,
                    "distresPernapasanBeratId" => $request->distresPernapasanBeratId,
                    "lainnyaId" => $request->lainnyaId,

                    "tanggal_ambil_data" => Carbon::now(),
                    "status_sinkron" => 0,
                    "tanggal_sinkron" => null,
                    "user_id" => auth()->user()->id
                ]);
                $response = $api->kirimData($covid);
                if($response->status) {
                    Covid::where('noRM', $request->noRM)
                    ->where('id', $covid->id)
                    ->where('tanggalMasuk', $request->tanggalMasuk)
                    ->update([
                        'status_sinkron' => 1,
                        'tanggal_sinkron' => Carbon::now(),
                        'user_id' => auth()->user()->id,
                        'id_laporan' => $response->data->id
                    ]);
                    return JsonResponseController::jsonDataWithIcon($response->data->id, $response->message, 'success');
                }
                if($response->status == false) {
                    return JsonResponseController::jsonDataWithIcon(null, $response->message, 'error');
                }
            } else if ($request->header('tombol') == "Kirim Ulang") {
                $covid = Covid::updateOrCreate([
                    'noRM' => $request->noRM,
                    'tanggalMasuk' => $request->tanggalMasuk
                ],[
                    "kewarganegaraanId" => $request->kewarganegaraanId,
                    "nik" => $request->nik,
                    "noPassport" => $request->noPassport,
                    "noRM" => $request->noRM,
                    "namaLengkapPasien" => $request->namaLengkapPasien,
                    "namaInisialPasien" => $request->namaInisialPasien,
                    "tanggalLahir" => $request->tanggalLahir,
                    "email" => $request->email,
                    "noTelp" => $request->noTelp,
                    "jenisKelaminId" => $request->jenisKelaminId,
                    "asalPasienId" => $request->asalPasienId,
                    "domisiliProvinsiId" => $request->domisiliProvinsiId == -1 ? null: $request->domisiliProvinsiId,
                    "domisiliKabKotaId" => $request->domisiliKabKotaId == -1 ? null: $request->domisiliKabKotaId,
                    "domisiliKecamatanId" => $request->domisiliKecamatanId == -1 ? null: $request->domisiliKecamatanId,
                    "pekerjaanId" => $request->pekerjaanId,
                    "tanggalMasuk" => $request->tanggalMasuk,
                    "jenisPasienId" => $request->jenisPasienId,
                    "statusPasienId" => $request->statusPasienId,
                    "statusCoInsidenId" => $request->statusCoInsidenId,
                    "statusRawatId" => $request->statusRawatId,
                    "alatOksigenId" => $request->alatOksigenId == -1 ? null: $request->alatOksigenId,
                    "penyintasId" => $request->penyintasId,
                    "tanggalOnsetGejala" => $request->tanggalOnsetGejala,
                    "kelompokGejalaId" => $request->kelompokGejalaId,
                    "demamId" => $request->demamId,
                    "batukId" => $request->batukId,
                    "pilekId" => $request->pilekId,
                    "sakitTenggorokanId" => $request->sakitTenggorokanId,
                    "sesakNapasId" => $request->sesakNapasId,
                    "lemasId" => $request->lemasId,
                    "nyeriOtotId" => $request->nyeriOtotId,
                    "mualMuntahId" => $request->mualMuntahId,
                    "diareId" => $request->diareId,
                    "anosmiaId" => $request->anosmiaId,
                    "napasCepatId" => $request->napasCepatId,
                    "frekNapas30KaliPerMenitId" => $request->frekNapas30KaliPerMenitId,
                    "distresPernapasanBeratId" => $request->distresPernapasanBeratId,
                    "lainnyaId" => $request->lainnyaId,

                    "tanggal_ambil_data" => Carbon::now(),
                    "status_sinkron" => 1,
                    "tanggal_sinkron" => Carbon::now(),
                    "user_id" => auth()->user()->id
                ]);
                
                $response = $api->updateData($covid);
                if($response->status) {
                    Covid::where('noRM', $request->noRM)
                    ->where('id', $covid->id)
                    ->where('tanggalMasuk', $request->tanggalMasuk)
                    ->update([
                        'id_laporan' => $response->data->id
                    ]);
                    return JsonResponseController::jsonDataWithIcon($response->data->id, $response->message, 'success');
                }
                if($response->status == false) {
                    return JsonResponseController::jsonDataWithIcon(null, $response->message, 'error');
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Covid  $covid
     * @return \Illuminate\Http\Response
     */
    public function show(Covid $covid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Covid  $covid
     * @return \Illuminate\Http\Response
     */
    public function edit(Covid $covid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Covid  $covid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Covid $covid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Covid  $covid
     * @return \Illuminate\Http\Response
     */
    public function destroy(Covid $covid)
    {
        //
    }

    private function selectPatient() {
        return [
            'patient.PatientName',
            'patient.SSN',
            'patient.MedicalNo',
            'patient.CityOfBirth',
            'patient.GCSex',
            'patient.GCEducation',
            'patient.GCMaritalStatus',
            'patient.GCNationality',
            'patient.GCOccupation',
            'patient.GCReligion',
            'patient.MobilePhoneNo1',
            'patient.MobilePhoneNo2'
        ];
    }

    private function selectRegistration() {
        return [
            'registration.RegistrationNo'
        ];
    }
}
