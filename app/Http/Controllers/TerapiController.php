<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use App\Models\PatientProblem;
use App\Models\Registration;
use App\Models\Terapi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TerapiController extends Controller
{
    public function cekData($medicalno, $registration, $problem)
    {
        $covid = Covid::where('noRM', $medicalno)
                    ->where('tanggalMasuk', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
                    ->whereNotNull('id_laporan')
                    ->where('status_sinkron', 1)
                    ->first();

        if($covid) {
            $terapi = Terapi::where('id_laporan', $covid->id_laporan)
                                ->where('OnsetDate', Carbon::parse($problem->OnsetDate)->format('Y-m-d'))
                                ->where('terapiId',$problem->terapiId)
                                ->first();

            if($terapi) {
                if($terapi->status_sinkron == 0) {
                    return [
                        'warna' => 'info',
                        'pesan' => 'Data disimpan'
                    ];
                } else if ($terapi->status_sinkron == 1) {
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

    public function dt_terapi()
    {
        $api = new ApiController;
        $t = $this->parseObat($api->dataobatcovid());
        if(request()->ajax()) {
            $registration = Registration::where('RegistrationNo', request()->registrationno)->first();

            // // List Diagnosa
            // $diagnosisPatient = PatientProblem::where('MedicalNo', request()->noRM)
            // // ->where('OnsetDate', Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d'))
            // ->whereIn('DiagnosisCode', $terapi)
            // ->orderByDesc('OnsetDate')
            // ->get();

            $terapi = Terapi::where('id_laporan', request()->id_laporan)->get();
            return datatables()
                ->of($terapi)
                ->addIndexColumn()
                ->editColumn('action', function($row) use ($registration) {
                    $cek = $this->cekData(request()->noRM, $registration, $row);
                    $button = '<div class="btn-group btn-group-sm" role="group">';
                    $button .= '<span class="p-1 text-white bg-'.$cek['warna'].'"><i class="fas fa-exclamation"></i> '.$cek['pesan'].'</span>';
                    $button .= '</div>';

                    return $button;
                })
                ->editColumn('terapiId', function($row) use ($t) {
                    if(array_key_exists($row->terapiId, $t)) {
                        return $row->terapiId.'-'.$t[$row->terapiId];
                    } else {
                        return 'Not found';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function parseObat($data)
    {
        $arr = [];
        foreach($data as $item) {
            $exp = explode('-', $item[0], 2);
            $arr[$exp[0]] = $exp[1];
        }
        return $arr;
    }

    public function select_dataobat(Request $request)
    {
        $search = $request->search;

        $api = new ApiController;
        $terapi = $this->parseObat($api->dataobatcovid());

        if($search == ''){
            $datas = $terapi;
        }else{
            foreach($terapi as $k => $v)
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

    public function tambah_terapi(Request $request)
    {
        $post = Terapi::updateOrCreate([
            'id_laporan' => $request->id_laporan,
            'terapiId' => $request->req,
        ],[
            'id_laporan' => $request->id_laporan,
            'laporanCovid19Versi3Id' => $request->id_laporan,
            'terapiId' => $request->req,
            'jumlahTerapi' => $request->jumlah_terapi,
            'OnsetDate' => Carbon::parse($request->registration)->format('Y-m-d'),

            'tanggal_ambil_data' => Carbon::now(),
            'user_id' => auth()->user()->id
        ]);

        return JsonResponseController::jsonDataWithIcon($post, 'Berhasil ditambah!');
    }

    public function oneParseTerapi($data)
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
                return JsonResponseController::jsonDataWithIcon(null, 'Terjadi kesalahan!', 'error');
            } else if ($request->header('tombol') == "Update") {

            } else if ($request->header('tombol') == "Kirim") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                foreach($request->data as $req) {
                    $covid = Terapi::updateOrCreate([
                        'id_laporan' => $id_laporan,
                        'terapiId' => $this->oneParseTerapi($req[0]["terapiId"]),
                        'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                    ],[
                        "terapiId" => $this->oneParseTerapi($req[0]["terapiId"]),
                        'jumlahTerapi' => $req[0]['jumlahTerapi'],
                        "OnsetDate" => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                        "tanggal_ambil_data" => Carbon::now(),
                        "user_id" => auth()->user()->id
                    ]);
                }
                $newTerapi = Terapi::where('id_laporan', $request->covid)->get();
                $response = $api->kirimDataTerapi($newTerapi);
                if($response->status) {
                    Terapi::where('id_laporan', $request->covid)
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
                    $covid = Terapi::updateOrCreate([
                        'id_laporan' => $id_laporan,
                        'terapiId' => $this->oneParseTerapi($req[0]["terapiId"]),
                        'OnsetDate' => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d')
                    ],[
                        "terapiId" => $this->oneParseTerapi($req[0]["terapiId"]),
                        'jumlahTerapi' => $req[0]['jumlahTerapi'],
                        "OnsetDate" => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                        "tanggal_ambil_data" => Carbon::now(),
                        "user_id" => auth()->user()->id
                    ]);
                }
                
                $newTerapi = Terapi::where('id_laporan', $request->covid)->get();
                $response = $api->updateDataTerapi($newTerapi);
                if($response->status) {
                    Terapi::where('id_laporan', $request->covid)
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
