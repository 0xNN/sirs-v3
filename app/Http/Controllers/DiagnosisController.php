<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use App\Models\Diagnosa;
use App\Models\PatientProblem;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function cekData($medicalno, $registration, $problem)
    {
        $covid = Covid::where('noRM', $medicalno)
                    ->where('tanggalMasuk', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
                    ->whereNotNull('id_laporan')
                    ->where('status_sinkron', 1)
                    ->first();

        if($covid) {
            // dd($problem->OnsetDate);
            $diagnosa = Diagnosa::where('id_laporan', $covid->id_laporan)
                                ->where('OnsetDate', Carbon::parse($problem->OnsetDate)->format('Y-m-d'))
                                ->where('diagnosaId',$problem->DiagnosisCode)
                                ->first();

            // dd($diagnosa);

            if($diagnosa) {
                if($diagnosa->status_sinkron == 0) {
                    return [
                        'warna' => 'info',
                        'pesan' => 'Data disimpan'
                    ];
                } else if ($diagnosa->status_sinkron == 1) {
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

    public function dt_diagnosis()
    {
        if(request()->ajax()) {
            $registration = Registration::where('RegistrationNo', request()->registrationno)->first();

            // List Diagnosa
            $diagnosisPatient = PatientProblem::where('MedicalNo', request()->noRM)
            // ->where('OnsetDate', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
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
                ->editColumn('Level', function($row) {
                    if(in_array($row->DiagnosisCode, config('myconfig.code_covid'))) {
                        $button = '<span class="p-1 text-white bg-danger">Primary</span>';
                    } else {
                        $button = '<span class="p-1 text-white bg-info">Secondary</span>';
                    }

                    return $button;
                })
                ->editColumn('DiagnosisName', function($row) {
                    return $row->diagnosa->DiagnosisName;
                })
                ->editColumn('ICDBlockID', function($row) {
                    return $row->diagnosa->icdblock->ICDBlockID;
                })
                ->editColumn('ICDBlockName', function($row) {
                    return $row->diagnosa->icdblock->ICDBlockName;
                })
                ->escapeColumns([])
                ->make(true);
        }
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
                $i = 0;
                foreach($request->data as $r) {
                    if(preg_match("/Primary/i", $r[0]['Level'])) {
                        $i += 1;
                    }
                }
                if($i > 1) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak bisa input Primary Diagnosa lebih dari 1', 'error');
                }
                foreach($request->data as $req) {
                    if(preg_match("/Primary/i", $req[0]['Level'])) {
                        $idLevel = 1;
                    } else {
                        $idLevel = 2;
                    }
                    
                    $covid = Diagnosa::create([
                        "id_laporan" => $id_laporan,
                        "laporanCovid19Versi3Id" => $id_laporan,
                        "diagnosaLevelId" => $idLevel,
                        "diagnosaId" => $req[0]["DiagnosisCode"],
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
                $i = 0;
                foreach($request->data as $r) {
                    if(preg_match("/Primary/i", $r[0]['Level'])) {
                        $i += 1;
                    }
                }
                if($i > 1) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak bisa input Primary Diagnosa lebih dari 1', 'error');
                }
                
                $g = 0;
                $u = 0;
                $s = 0;
                foreach($request->data as $req) {
                    if(preg_match("/Primary/i", $req[0]['Level'])) {
                        $idLevel = 1;
                    } else {
                        $idLevel = 2;
                    }
                    
                    $cek = Diagnosa::where('id_laporan', $id_laporan)
                                ->where('OnsetDate', Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'))
                                ->where('diagnosaId', $req[0]["DiagnosisCode"])
                                ->first();

                    if($cek) {
                        $covid = Diagnosa::updateOrCreate([
                            'id_laporan' => $id_laporan,
                            'diagnosaId' => $req[0]["DiagnosisCode"],
                            'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                        ],[
                            "diagnosaLevelId" => $idLevel,
                            "diagnosaId" => $req[0]["DiagnosisCode"],
                            "tanggal_ambil_data" => Carbon::now(),
                            "user_id" => auth()->user()->id
                        ]);
                        $u += 1;
                    } else {
                        $cek2 = Diagnosa::where('id_laporan', $id_laporan)
                                        ->where('diagnosaId', $req[0]['DiagnosisCode'])
                                        ->where('OnsetDate', Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'))
                                        ->where('diagnosaLevelId', $idLevel)
                                        ->first();

                        if($cek2) {
                            $covid = Diagnosa::updateOrCreate([
                                'id_laporan' => $id_laporan,
                                'diagnosaId' => $req[0]["DiagnosisCode"],
                                'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                            ],[
                                "diagnosaLevelId" => $idLevel,
                                "diagnosaId" => $req[0]["DiagnosisCode"],
                                "tanggal_ambil_data" => Carbon::now(),
                                "user_id" => auth()->user()->id
                            ]);
                            $u += 1;
                        } else {
                            if($idLevel == 2) {
                                $covid = Diagnosa::create([
                                    'id_laporan' => $id_laporan,
                                    'laporanCovid19Versi3Id' => $id_laporan,
                                    "diagnosaLevelId" => $idLevel,
                                    "diagnosaId" => $req[0]["DiagnosisCode"],
                                    'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
    
                                    "tanggal_ambil_data" => Carbon::now(),
                                    "user_id" => auth()->user()->id
                                ]);
                                $s += 1;
                            } else if($idLevel == 1) {
                                $covid = null;
                                $g += 1;
                            }
                        }
                    }
                }
                if($g > 0) {
                    return JsonResponseController::jsonDataGUS(
                        $covid, 
                        $s.' Data disimpan, '.$u.' Data diupdate, '.$g.' Gagal update!', 
                        'warning', 
                        [
                            'g' => $g,
                            'u' => $u,
                            's' => $s
                        ]
                    );
                } else {
                    return JsonResponseController::jsonDataGUS(
                        $covid, 
                        $s.' Data disimpan, '.$u.' Data diupdate, '.$g.' Gagal update!',
                        'success',
                        [
                            'g' => $g,
                            'u' => $u,
                            's' => $s
                        ]
                    );
                }
            } else if ($request->header('tombol') == "Kirim") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                $i = 0;
                foreach($request->data as $r) {
                    if(preg_match("/Primary/i", $r[0]['Level'])) {
                        $i += 1;
                    }
                }
                if($i > 1) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak bisa input Primary Diagnosa lebih dari 1', 'error');
                }
                $g = 0;
                $u = 0;
                $s = 0;
                
                foreach($request->data as $req) {
                    if(preg_match("/Primary/i", $req[0]['Level'])) {
                        $idLevel = 1;
                    } else {
                        $idLevel = 2;
                    }
                    
                    $cek = Diagnosa::where('id_laporan', $id_laporan)
                                ->where('OnsetDate', Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'))
                                ->where('diagnosaId', $req[0]["DiagnosisCode"])
                                ->first();

                    if($cek) {
                        $covid = Diagnosa::updateOrCreate([
                            'id_laporan' => $id_laporan,
                            'diagnosaId' => $req[0]["DiagnosisCode"],
                            'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                        ],[
                            "diagnosaLevelId" => $idLevel,
                            "diagnosaId" => $req[0]["DiagnosisCode"],
                            "tanggal_ambil_data" => Carbon::now(),
                            "user_id" => auth()->user()->id
                        ]);
                        $u += 1;
                    } else {
                        $cek2 = Diagnosa::where('id_laporan', $id_laporan)
                                        ->where('diagnosaId', $req[0]['DiagnosisCode'])
                                        ->where('OnsetDate', Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'))
                                        ->where('diagnosaLevelId', $idLevel)
                                        ->first();

                        if($cek2) {
                            $covid = Diagnosa::updateOrCreate([
                                'id_laporan' => $id_laporan,
                                'diagnosaId' => $req[0]["DiagnosisCode"],
                                'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                            ],[
                                "diagnosaLevelId" => $idLevel,
                                "diagnosaId" => $req[0]["DiagnosisCode"],
                                "tanggal_ambil_data" => Carbon::now(),
                                "user_id" => auth()->user()->id
                            ]);
                            $u += 1;
                        } else {
                            if($idLevel == 2) {
                                $covid = Diagnosa::create([
                                    'id_laporan' => $id_laporan,
                                    'laporanCovid19Versi3Id' => $id_laporan,
                                    "diagnosaLevelId" => $idLevel,
                                    "diagnosaId" => $req[0]["DiagnosisCode"],
                                    'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
    
                                    "tanggal_ambil_data" => Carbon::now(),
                                    "user_id" => auth()->user()->id
                                ]);
                                $s += 1;
                            } else if($idLevel == 1) {
                                $covid = null;
                                $g += 1;
                            }
                        }
                    }
                }
                $newDiagnosa = Diagnosa::where('id_laporan', $request->covid)->get();
                $response = $api->kirimDataDiagnosa($newDiagnosa);
                if($response->status) {
                    Diagnosa::where('id_laporan', $request->covid)
                    ->update([
                        'status_sinkron' => 1,
                        'tanggal_sinkron' => Carbon::now(),
                        'user_id' => auth()->user()->id,
                        'id_diagnosa' => $response->data->id
                    ]);
                    return JsonResponseController::jsonDataWithIcon($response->data->id, $response->message, 'success');
                }
                return JsonResponseController::jsonDataWithIcon(null, $response->message, 'error');
            } else if ($request->header('tombol') == "Kirim Ulang") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                $i = 0;
                foreach($request->data as $r) {
                    if(preg_match("/Primary/i", $r[0]['Level'])) {
                        $i += 1;
                    }
                }
                if($i > 1) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak bisa input Primary Diagnosa lebih dari 1', 'error');
                }
                $g = 0;
                $u = 0;
                $s = 0;
                
                foreach($request->data as $req) {
                    if(preg_match("/Primary/i", $req[0]['Level'])) {
                        $idLevel = 1;
                    } else {
                        $idLevel = 2;
                    }
                    
                    $cek = Diagnosa::where('id_laporan', $id_laporan)
                                ->where('OnsetDate', Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'))
                                ->where('diagnosaId', $req[0]["DiagnosisCode"])
                                ->first();

                    if($cek) {
                        $covid = Diagnosa::updateOrCreate([
                            'id_laporan' => $id_laporan,
                            'diagnosaId' => $req[0]["DiagnosisCode"],
                            'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                        ],[
                            "diagnosaLevelId" => $idLevel,
                            "diagnosaId" => $req[0]["DiagnosisCode"],
                            "tanggal_ambil_data" => Carbon::now(),
                            "user_id" => auth()->user()->id
                        ]);
                        $u += 1;
                    } else {
                        $cek2 = Diagnosa::where('id_laporan', $id_laporan)
                                        ->where('diagnosaId', $req[0]['DiagnosisCode'])
                                        ->where('OnsetDate', Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'))
                                        ->where('diagnosaLevelId', $idLevel)
                                        ->first();

                        if($cek2) {
                            $covid = Diagnosa::updateOrCreate([
                                'id_laporan' => $id_laporan,
                                'diagnosaId' => $req[0]["DiagnosisCode"],
                                'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                            ],[
                                "diagnosaLevelId" => $idLevel,
                                "diagnosaId" => $req[0]["DiagnosisCode"],
                                "tanggal_ambil_data" => Carbon::now(),
                                "user_id" => auth()->user()->id
                            ]);
                            $u += 1;
                        } else {
                            if($idLevel == 2) {
                                $covid = Diagnosa::create([
                                    'id_laporan' => $id_laporan,
                                    'laporanCovid19Versi3Id' => $id_laporan,
                                    "diagnosaLevelId" => $idLevel,
                                    "diagnosaId" => $req[0]["DiagnosisCode"],
                                    'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
    
                                    "tanggal_ambil_data" => Carbon::now(),
                                    "user_id" => auth()->user()->id
                                ]);
                                $s += 1;
                            } else if($idLevel == 1) {
                                $covid = null;
                                $g += 1;
                            }
                        }
                    }
                }
                
                $newDiagnosa = Diagnosa::where('id_laporan', $request->covid)->get();
                $response = $api->updateDataDiagnosa($newDiagnosa);
                if($response->status) {
                    Diagnosa::where('id_laporan', $request->covid)
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
