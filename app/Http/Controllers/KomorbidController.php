<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use App\Models\Komorbid;
use App\Models\PatientProblem;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KomorbidController extends Controller
{
    public function cekData($medicalno, $registration, $problem)
    {
        $covid = Covid::where('noRM', $medicalno)
                    ->where('tanggalMasuk', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
                    ->whereNotNull('id_laporan')
                    ->where('status_sinkron', 1)
                    ->first();

        if($covid) {
            $komorbid = Komorbid::where('id_laporan', $covid->id_laporan)
                                ->where('OnsetDate', Carbon::parse($problem->OnsetDate)->format('Y-m-d'))
                                ->where('komorbidId',$problem->DiagnosisCode)
                                ->first();

            if($komorbid) {
                if($komorbid->status_sinkron == 0) {
                    return [
                        'warna' => 'info',
                        'pesan' => 'Data disimpan'
                    ];
                } else if ($komorbid->status_sinkron == 1) {
                    return [
                        'warna' => 'success',
                        'pesan' => 'Data disimpan dan dikirim'
                    ];
                }
            } else {
                return [
                    'warna' => 'warning',
                    'pesan' => 'Belum disimpan dan dikirim'
                ];
            }
        }
    }

    public function dt_komorbid()
    {
        $api = new ApiController;
        $komorbid = $this->parseKomorbid($api->datakomorbidcovid());
        // dd($komorbid);
        if(request()->ajax()) {
            $registration = Registration::where('RegistrationNo', request()->registrationno)->first();

            // List Diagnosa
            $diagnosisPatient = PatientProblem::where('MedicalNo', request()->noRM)
            // ->where('OnsetDate', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
            ->whereIn('DiagnosisCode', $komorbid)
            ->orderByDesc('OnsetDate')
            ->get();

            return datatables()
                ->of($diagnosisPatient)
                ->addIndexColumn()
                ->editColumn('action', function($row) use ($registration) {
                    $cek = $this->cekData(request()->noRM, $registration, $row);
                    $button = '<div class="btn-group btn-group-sm" role="group">';
                    $button .= '<span class="p-1 text-white bg-'.$cek['warna'].'"><i class="fas fa-exclamation"></i> '.$cek['pesan'].'</span>';
                    $button .= '</div>';

                    return $button;
                })
                ->editColumn('DiagnosisCode', function($row) use ($komorbid) {
                    if(in_array($row->DiagnosisCode, $komorbid)) {
                        return $row->DiagnosisCode.'-'.$row->diagnosa->DiagnosisName;
                    } else {
                        return 'Not found';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
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

    public function oneParseKomorbid($data)
    {
        $exp = explode('-', $data);
        return $exp[0];
    }

    public function store(Request $request)
    {
        $api = new ApiController;
        if($request->hasHeader('tombol')) {
            if($request->header('tombol') == "Simpan") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                foreach($request->data as $req) {
                    $covid = Komorbid::create([
                        "id_laporan" => $id_laporan,
                        "laporanCovid19Versi3Id" => $id_laporan,
                        "komorbidId" => $this->oneParseKomorbid($req[0]["DiagnosisCode"]),
                        "OnsetDate" => $req[0]["OnsetDate"],

                        "tanggal_ambil_data" => Carbon::now(),
                        "status_sinkron" => 0,
                        "tanggal_sinkron" => null,
                        "user_id" => auth()->user()->id
                    ]);
                }
                return JsonResponseController::jsonDataWithIcon($covid, 'Data disimpan!');
            } else if ($request->header('tombol') == "Update") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                foreach($request->data as $req) {
                    $covid = Komorbid::updateOrCreate([
                        'id_laporan' => $id_laporan,
                        'komorbidId' => $this->oneParseKomorbid($req[0]["DiagnosisCode"]),
                        'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                    ],[
                        "komorbidId" => $this->oneParseKomorbid($req[0]["DiagnosisCode"]),
                        "OnsetDate" => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                        "tanggal_ambil_data" => Carbon::now(),
                        "user_id" => auth()->user()->id
                    ]);
                }
                return JsonResponseController::jsonDataWithIcon($covid, 'Berhasil di Simpan!', 'success');
            } else if ($request->header('tombol') == "Kirim") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                foreach($request->data as $req) {
                    $covid = Komorbid::updateOrCreate([
                        'id_laporan' => $id_laporan,
                        'komorbidId' => $this->oneParseKomorbid($req[0]["DiagnosisCode"]),
                        'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                    ],[
                        "komorbidId" => $this->oneParseKomorbid($req[0]["DiagnosisCode"]),
                        "OnsetDate" => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                        "tanggal_ambil_data" => Carbon::now(),
                        "user_id" => auth()->user()->id
                    ]);
                }
                $newKomorbid = Komorbid::where('id_laporan', $request->covid)->get();
                $response = $api->kirimDataKomorbid($newKomorbid);
                if($response->status) {
                    Komorbid::where('id_laporan', $request->covid)
                    ->update([
                        'status_sinkron' => 1,
                        'tanggal_sinkron' => Carbon::now(),
                        'user_id' => auth()->user()->id,
                        'id_komorbid' => $response->data->id
                    ]);
                    return JsonResponseController::jsonDataWithIcon($response->data->id, $response->message, 'success');
                }
                return JsonResponseController::jsonDataWithIcon(null, $response->message, 'error');
            } else if ($request->header('tombol') == "Kirim Ulang") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }

                foreach($request->data as $req) {
                    $covid = Komorbid::updateOrCreate([
                        'id_laporan' => $id_laporan,
                        'komorbidId' => $this->oneParseKomorbid($req[0]["DiagnosisCode"]),
                        'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                    ],[
                        "komorbidId" => $this->oneParseKomorbid($req[0]["DiagnosisCode"]),
                        'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                        "tanggal_ambil_data" => Carbon::now(),
                        "user_id" => auth()->user()->id
                    ]);
                }
                
                $newKomorbid = Komorbid::where('id_laporan', $request->covid)->get();
                $response = $api->updateDataKomorbid($newKomorbid);
                if($response->status) {
                    Komorbid::where('id_laporan', $request->covid)
                    ->update([
                        'status_sinkron' => 1,
                        'tanggal_sinkron' => Carbon::now(),
                        'user_id' => auth()->user()->id,
                    ]);
                    return JsonResponseController::jsonDataWithIcon($response->data->id, $response->message, 'success');
                }
                return JsonResponseController::jsonDataWithIcon(null, $response->message, 'error');
            }
        }
    }
}
