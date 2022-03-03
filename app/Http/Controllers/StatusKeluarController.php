<?php

namespace App\Http\Controllers;

use App\Models\StatusKeluar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatusKeluarController extends Controller
{
    public function store(Request $request)
    {
        $api = new ApiController;
        if($request->hasHeader('tombol')) {
            if($request->header('tombol') == "Simpan") {
            } else if ($request->header('tombol') == "Update") {

            } else if ($request->header('tombol') == "Kirim") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                $covid = StatusKeluar::updateOrCreate([
                    'id_laporan' => $id_laporan,
                ],[
                    'laporanCovid19Versi3Id' => $id_laporan,
                    'tanggalKeluar' => $request->data['tanggalKeluar'],
                    'statusKeluarId' => $request->data['statusKeluarId'] == -1 ? 0: $request->data['statusKeluarId'],
                    'kasusKematianId' => $request->data["kasusKematianId"],
                    'penyebabKematianLangsungId' => $request->data['penyebabKematianLangsungId'],
                    // "OnsetDate" => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                    "tanggal_ambil_data" => Carbon::now(),
                    "user_id" => auth()->user()->id
                ]);
                $response = $api->kirimDataStatusKeluar($covid);
                if($response->status) {
                    StatusKeluar::where('id_laporan', $request->covid)
                    ->update([
                        'status_sinkron' => 1,
                        'tanggal_sinkron' => Carbon::now(),
                        'user_id' => auth()->user()->id,
                        'id_statuskeluar' => $response->data->id
                    ]);
                    return JsonResponseController::jsonDataWithIcon($response->data->id, $response->message, 'success');
                }
                return JsonResponseController::jsonDataWithIcon(null, $response->message, 'error');
            } else if ($request->header('tombol') == "Kirim Ulang") {
                $id_laporan = $request->covid;
                if($request->data == null) {
                    return JsonResponseController::jsonDataWithIcon(null, 'Tidak ada data yang disimpan!', 'error');
                }
                $covid = StatusKeluar::updateOrCreate([
                    'id_laporan' => $id_laporan,
                ],[
                    'laporanCovid19Versi3Id' => $id_laporan,
                    'tanggalKeluar' => $request->data['tanggalKeluar'],
                    'statusKeluarId' => $request->data['statusKeluarId'],
                    'kasusKematianId' => $request->data["kasusKematianId"],
                    'penyebabKematianLangsungId' => $request->data['penyebabKematianLangsungId'],
                    // "OnsetDate" => Carbon::parse($req[0]["OnsetDate"])->format('Y-m-d'),
                    "tanggal_ambil_data" => Carbon::now(),
                    "user_id" => auth()->user()->id
                ]);
                $response = $api->updateDataStatusKeluar($covid);
                if($response->status) {
                    StatusKeluar::where('id_laporan', $request->covid)
                    ->update([
                        'status_sinkron' => 1,
                        'tanggal_sinkron' => Carbon::now(),
                        'user_id' => auth()->user()->id,
                        'id_statuskeluar' => $response->data->id
                    ]);
                    return JsonResponseController::jsonDataWithIcon($response->data->id, $response->message, 'success');
                }
                return JsonResponseController::jsonDataWithIcon(null, $response->message, 'error');
            }
        }
    }
}
