<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use App\Models\Registration;
use App\Models\Vaksinasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VaksinasiController extends Controller
{
    public function cekData($medicalno, $registration, $problem)
    {
        $covid = Covid::where('noRM', $medicalno)
                    ->where('tanggalMasuk', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
                    ->whereNotNull('id_laporan')
                    ->where('status_sinkron', 1)
                    ->first();

        if($covid) {
            $vaksinasi = Vaksinasi::where('id_laporan', $covid->id_laporan)
                                ->where('OnsetDate', Carbon::parse($problem->OnsetDate)->format('Y-m-d'))
                                ->where('dosisVaksinId',$problem->dosisVaksinId)
                                ->where('jenisVaksinId',$problem->jenisVaksinId)
                                ->first();

            if($vaksinasi) {
                if($vaksinasi->status_sinkron == 0) {
                    return [
                        'warna' => 'info',
                        'pesan' => 'Data disimpan'
                    ];
                } else if ($vaksinasi->status_sinkron == 1) {
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

    public function dt_vaksinasi()
    {
        $api = new ApiController;
        $dosis_vaksin = $this->dosis_vaksin();
        $jenis_vaksin = $this->jenis_vaksin();
        if(request()->ajax()) {
            $registration = Registration::where('RegistrationNo', request()->registrationno)->first();

            // // List Diagnosa
            // $diagnosisPatient = PatientProblem::where('MedicalNo', request()->noRM)
            // // ->where('OnsetDate', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
            // ->whereIn('DiagnosisCode', $terapi)
            // ->orderByDesc('OnsetDate')
            // ->get();

            $vaksinasi = Vaksinasi::where('id_laporan', request()->id_laporan)->get();
            return datatables()
                ->of($vaksinasi)
                ->addIndexColumn()
                ->editColumn('action', function($row) use ($registration) {
                    $cek = $this->cekData(request()->noRM, $registration, $row);
                    $button = '<div class="btn-group btn-group-sm" role="group">';
                    $button .= '<span class="p-1 text-white bg-'.$cek['warna'].'"><i class="fas fa-exclamation"></i> '.$cek['pesan'].'</span>';
                    $button .= '</div>';

                    return $button;
                })
                ->editColumn('dosisVaksinId', function($row) use ($dosis_vaksin) {
                    if(array_key_exists($row->dosisVaksinId, $dosis_vaksin)) {
                        return $row->dosisVaksinId.'-'.$dosis_vaksin[$row->dosisVaksinId];
                    } else {
                        return 'Not found';
                    }
                })
                ->editColumn('jenisVaksinId', function($row) use ($jenis_vaksin) {
                    if(array_key_exists($row->jenisVaksinId, $jenis_vaksin)) {
                        return $row->jenisVaksinId.'-'.$jenis_vaksin[$row->jenisVaksinId];
                    } else {
                        return 'Not found';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function dosis_vaksin()
    {
        return [
            0 => 'Belum Vaksinasi',
            1 => 'Vaksinasi Ke-1',
            3 => 'Vaksinasi Ke-2',
            4 => 'Booster'
        ];
    }

    public function jenis_vaksin()
    {
        return [
            0 => 'Belum Vaksin',
            1 => 'Sinovac',
            2 => 'Vaksin Covid-19 Biofarma',
            3 => 'AstraZeneca',
            4 => 'Sinopharm',
            5 => 'Moderna',
            6 => 'Pfizer',
            7 => 'Sputnik V',
            8 => 'Janseen',
            9 => 'Covidencia'
        ];
    }

    public function tambah_vaksinasi(Request $request)
    {
        $cek = Vaksinasi::where('dosisVaksinId',$request->dosisVaksinId)
                        ->where('id_laporan', $request->id_laporan)
                        ->first();

        $post = Vaksinasi::updateOrCreate([
            'id_laporan' => $request->id_laporan,
            'id' => $cek == null ? null: $cek->id,
        ],[
            'id_laporan' => $request->id_laporan,
            'laporanCovid19Versi3Id' => $request->id_laporan,
            'dosisVaksinId' => $request->dosisVaksinId,
            'jenisVaksinId' => $request->jenisVaksinId,
            'OnsetDate' => Carbon::parse($request->registration)->format('Y-m-d'),

            'tanggal_ambil_data' => Carbon::now(),
            'user_id' => auth()->user()->id
        ]);

        return JsonResponseController::jsonDataWithIcon($post, 'Berhasil ditambah!');
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
                return JsonResponseController::jsonDataWithIcon(null, 'Terjadi kesalahan!', 'error');
            } else if ($request->header('tombol') == "Update") {

            } else if ($request->header('tombol') == "Kirim") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                foreach($request->data as $req) {
                    $covid = Vaksinasi::updateOrCreate([
                        'id' => $req[0]["id"],
                        'id_laporan' => $id_laporan,
                        'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                    ],[
                        'dosisVaksinId' => $this->oneParseVaksinasi($req[0]["dosisVaksinId"]),
                        'jenisVaksinId' => $this->oneParseVaksinasi($req[0]["jenisVaksinId"]),
                        "OnsetDate" => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                        "tanggal_ambil_data" => Carbon::now(),
                        "user_id" => auth()->user()->id
                    ]);
                }
                $newVaksinasi = Vaksinasi::where('id_laporan', $request->covid)->get();
                $response = $api->kirimDataVaksinasi($newVaksinasi);
                if($response->status) {
                    Vaksinasi::where('id_laporan', $request->covid)
                    ->update([
                        'status_sinkron' => 1,
                        'tanggal_sinkron' => Carbon::now(),
                        'user_id' => auth()->user()->id,
                        'id_terapi' => $response->data->id
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
                    $covid = Vaksinasi::updateOrCreate([
                        'id' => $req[0]["id"],
                        'id_laporan' => $id_laporan,
                        'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                    ],[
                        'dosisVaksinId' => $this->oneParseVaksinasi($req[0]["dosisVaksinId"]),
                        'jenisVaksinId' => $this->oneParseVaksinasi($req[0]["jenisVaksinId"]),
                        "OnsetDate" => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                        "tanggal_ambil_data" => Carbon::now(),
                        "user_id" => auth()->user()->id
                    ]);
                }
                
                $newVaksinasi = Vaksinasi::where('id_laporan', $request->covid)->get();
                $response = $api->updateDataVaksinasi($newVaksinasi);
                if($response->status) {
                    Vaksinasi::where('id_laporan', $request->covid)
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

    public function select_dosisvaksin(Request $request)
    {
        $search = $request->search;

        $dosis_vaksin = $this->dosis_vaksin();
        if($search == ''){
            $datas = $dosis_vaksin;
        }else{
            foreach($dosis_vaksin as $k => $v)
            {
                if(stripos($v, $search) !== false) {
                    $datas[$k] = $v;
                }
            }
        }

        $response = array();
        foreach($datas as $key => $data){
            $response[] = array(
                "id"=>$key,
                "text"=>$key.'-'.$data
            );
        }

        return response()->json($response);
    }

    public function select_jenisvaksin(Request $request)
    {
        $search = $request->search;

        $jenis_vaksin = $this->jenis_vaksin();
        if($search == ''){
            $datas = $jenis_vaksin;
        }else{
            foreach($jenis_vaksin as $k => $v)
            {
                if(stripos($v, $search) !== false) {
                    $datas[$k] = $v;
                }
            }
        }

        $response = array();
        foreach($datas as $key => $data){
            $response[] = array(
                "id"=>$key,
                "text"=>$key.'-'.$data
            );
        }

        return response()->json($response);
    }

    public function oneParseVaksinasi($data)
    {
        $exp = explode('-', $data);
        return $exp[0];
    }
}
