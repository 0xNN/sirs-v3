<?php

namespace App\Http\Controllers;

use App\Models\NakesTerinfeksi;
use Exception;
use Illuminate\Http\Request;

class NakesTerinfeksiController extends Controller
{
    public function index(Request $request)
    {
        $tgl = null;
        if($request->hasHeader('tanggal')) {
            if($request->header('tanggal') != null) {
                $tgl = $request->header('tanggal');
            }
        }
        $api = new ApiPcrNakesController;
        $dataNakesTerinfeksi = null;
        try {
            $dataNakesTerinfeksi = $api->getNakesTerinfeksi($tgl);
        } catch (Exception $e) {
        }
        $count = NakesTerinfeksi::count();

        // dd($dataPcrNakes);
        if($dataNakesTerinfeksi != '202') {
            // if($count == 0) {
                if($dataNakesTerinfeksi != null) {
                    foreach($dataNakesTerinfeksi as $data) {
                        NakesTerinfeksi::updateOrCreate([
                            'tanggal' => $data->tanggal
                        ],[
                            'tanggal' => $data->tanggal,
                            'co_ass' => $data->co_ass,
                            'residen' => $data->residen,
                            'intership' => $data->intership,
                            'dokter_spesialis' => $data->dokter_spesialis,
                            'dokter_umum' => $data->dokter_umum,
                            'dokter_gigi' => $data->dokter_gigi,
                            'perawat' => $data->perawat,
                            'bidan' => $data->bidan,
                            'apoteker' => $data->apoteker,
                            'radiografer' => $data->radiografer,
                            'analis_lab' => $data->analis_lab,
                            'nakes_lainnya' => $data->nakes_lainnya,
                            'co_ass_meninggal' => $data->co_ass_meninggal,
                            'residen_meninggal' => $data->residen_meninggal,
                            'intership_meninggal' => $data->intership_meninggal,
                            'dokter_spesialis_meninggal' => $data->dokter_spesialis_meninggal,
                            'dokter_umum_meninggal' => $data->dokter_umum_meninggal,
                            'dokter_gigi_meninggal' => $data->dokter_gigi_meninggal,
                            'perawat_meninggal' => $data->perawat_meninggal,
                            'bidan_meninggal' => $data->bidan_meninggal,
                            'apoteker_meninggal' => $data->apoteker_meninggal,
                            'radiografer_meninggal' => $data->radiografer_meninggal,
                            'analis_lab_meninggal' => $data->analis_lab_meninggal,
                            'nakes_lainnya_meninggal' => $data->nakes_lainnya_meninggal,
                            'co_ass_dirawat' => $data->co_ass_dirawat,
                            'co_ass_isoman' => $data->co_ass_isoman,
                            'co_ass_sembuh' => $data->co_ass_sembuh,
                            'residen_dirawat' => $data->residen_dirawat,
                            'residen_isoman' => $data->residen_isoman,
                            'residen_sembuh' => $data->residen_sembuh,
                            'intership_dirawat' => $data->intership_dirawat,
                            'intership_isoman' => $data->intership_isoman,
                            'intership_sembuh' => $data->intership_sembuh,
                            'dokter_spesialis_dirawat' => $data->dokter_spesialis_dirawat,
                            'dokter_spesialis_isoman' => $data->dokter_spesialis_isoman,
                            'dokter_spesialis_sembuh' => $data->dokter_spesialis_sembuh,
                            'dokter_umum_dirawat' => $data->dokter_umum_dirawat,
                            'dokter_umum_isoman' => $data->dokter_umum_isoman,
                            'dokter_umum_sembuh' => $data->dokter_umum_sembuh,
                            'dokter_gigi_dirawat' => $data->dokter_gigi_dirawat,
                            'dokter_gigi_isoman' => $data->dokter_gigi_isoman,
                            'dokter_gigi_sembuh' => $data->dokter_gigi_sembuh,
                            'bidan_dirawat' => $data->bidan_dirawat,
                            'bidan_isoman' => $data->bidan_isoman,
                            'bidan_sembuh' => $data->bidan_sembuh,
                            'apoteker_dirawat' => $data->apoteker_dirawat,
                            'apoteker_isoman' => $data->apoteker_isoman,
                            'apoteker_sembuh' => $data->apoteker_sembuh,
                            'radiografer_dirawat' => $data->radiografer_dirawat,
                            'radiografer_isoman' => $data->radiografer_isoman,
                            'radiografer_sembuh' => $data->radiografer_sembuh,
                            'analis_lab_dirawat' => $data->analis_lab_dirawat,
                            'analis_lab_isoman' => $data->analis_lab_isoman,
                            'analis_lab_sembuh' => $data->analis_lab_sembuh,
                            'nakes_lainnya_dirawat' => $data->nakes_lainnya_dirawat,
                            'nakes_lainnya_isoman' => $data->nakes_lainnya_isoman,
                            'nakes_lainnya_sembuh' => $data->nakes_lainnya_sembuh,
                            'perawat_dirawat' => $data->perawat_dirawat,
                            'perawat_isoman' => $data->perawat_isoman,
                            'perawat_sembuh' => $data->perawat_sembuh,
    
                            'butuh_sinkron_ulang' => 0,
                            'status_sinkron' => 1,
                            'user_id' => auth()->user()->id,
                            'nama_user' => 'API'
                        ]);
                    }
                }
            // }
        }
    
        $nakesTerinfeksi = NakesTerinfeksi::all();
        if($request->ajax()) {
            return datatables()
                ->of($nakesTerinfeksi)
                ->addIndexColumn()
                ->editColumn('action', function($row) {
                    if(auth()->user()->role_id == 1) {
                        $button = '<div class="btn-group btn-group-sm" role="group">';
                        $button .= '<button id="btn-sinkron" data-id="'.$row->id.'" class="btn-sinkron btn btn-sm btn-info"><i class="fas fa-share"></i></button>';
                        // $button .= '<button id="btn-delete-sinkron" data-id="'.$row->id.'" class="btn-delete-sinkron btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                        $button .= '</div>';
                        return $button;
                    }
                    return '-';
                })
                ->editColumn('status', function($row) {
                    return $this->parseStatus($row);
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('pages.nakesterinfeksi.index');
    }

    public function spanActive($active)
    {
        if($active) {
            return '<span class="badge badge-success">Aktif</span>';
        }
        return '<span class="badge badge-danger">Tidak Aktif</span>';
    }

    public function parseHtmlStatus($warna = 'success', $pesan)
    {
        return '<span class="p-1 text-white bg-'.$warna.'"><i class="fas fa-exclamation"></i> '.$pesan.'</span>';
    }

    public function parseStatus($row)
    {
        if($row->butuh_sinkron_ulang == 1) {
            if($row->status_sinkron == 0) {
                return $this->parseHtmlStatus('danger', 'Kirim Data');
            } else {
                return $this->parseHtmlStatus('warning', 'Kirim Ulang');
            }
        }
        return $this->parseHtmlStatus('success', 'Terkirim');
    }

    public function create(Request $request)
    {
        return view('pages.nakesterinfeksi.create');
    }

    public function store(Request $request)
    {
        $data = $request->data;
        $saveData = [
            'tanggal' => $request->tanggal,
            'co_ass' => $data['co_ass'],
            'residen' => $data['residen'],
            'intership' => $data['intership'],
            'dokter_spesialis' => $data['dokter_spesialis'],
            'dokter_umum' => $data['dokter_umum'],
            'dokter_gigi' => $data['dokter_gigi'],
            'perawat' => $data['perawat'],
            'bidan' => $data['bidan'],
            'apoteker' => $data['apoteker'],
            'radiografer' => $data['radiografer'],
            'analis_lab' => $data['analis_lab'],
            'nakes_lainnya' => $data['nakes_lainnya'],
            'co_ass_meninggal' => $data['co_ass_meninggal'],
            'residen_meninggal' => $data['residen_meninggal'],
            'intership_meninggal' => $data['intership_meninggal'],
            'dokter_spesialis_meninggal' => $data['dokter_spesialis_meninggal'],
            'dokter_umum_meninggal' => $data['dokter_umum_meninggal'],
            'dokter_gigi_meninggal' => $data['dokter_gigi_meninggal'],
            'perawat_meninggal' => $data['perawat_meninggal'],
            'bidan_meninggal' => $data['bidan_meninggal'],
            'apoteker_meninggal' => $data['apoteker_meninggal'],
            'radiografer_meninggal' => $data['radiografer_meninggal'],
            'analis_lab_meninggal' => $data['analis_lab_meninggal'],
            'nakes_lainnya_meninggal' => $data['nakes_lainnya_meninggal'],
            'co_ass_dirawat' => $data['co_ass_dirawat'],
            'co_ass_isoman' => $data['co_ass_isoman'],
            'co_ass_sembuh' => $data['co_ass_sembuh'],
            'residen_dirawat' => $data['residen_dirawat'],
            'residen_isoman' => $data['residen_isoman'],
            'residen_sembuh' => $data['residen_sembuh'],
            'intership_dirawat' => $data['intership_dirawat'],
            'intership_isoman' => $data['intership_isoman'],
            'intership_sembuh' => $data['intership_sembuh'],
            'dokter_spesialis_dirawat' => $data['dokter_spesialis_dirawat'],
            'dokter_spesialis_isoman' => $data['dokter_spesialis_isoman'],
            'dokter_spesialis_sembuh' => $data['dokter_spesialis_sembuh'],
            'dokter_umum_dirawat' => $data['dokter_umum_dirawat'],
            'dokter_umum_isoman' => $data['dokter_umum_isoman'],
            'dokter_umum_sembuh' => $data['dokter_umum_sembuh'],
            'dokter_gigi_dirawat' => $data['dokter_gigi_dirawat'],
            'dokter_gigi_isoman' => $data['dokter_gigi_isoman'],
            'dokter_gigi_sembuh' => $data['dokter_gigi_sembuh'],
            'bidan_dirawat' => $data['bidan_dirawat'],
            'bidan_isoman' => $data['bidan_isoman'],
            'bidan_sembuh' => $data['bidan_sembuh'],
            'apoteker_dirawat' => $data['apoteker_dirawat'],
            'apoteker_isoman' => $data['apoteker_isoman'],
            'apoteker_sembuh' => $data['apoteker_sembuh'],
            'radiografer_dirawat' => $data['radiografer_dirawat'],
            'radiografer_isoman' => $data['radiografer_isoman'],
            'radiografer_sembuh' => $data['radiografer_sembuh'],
            'analis_lab_dirawat' => $data['analis_lab_dirawat'],
            'analis_lab_isoman' => $data['analis_lab_isoman'],
            'analis_lab_sembuh' => $data['analis_lab_sembuh'],
            'nakes_lainnya_dirawat' => $data['nakes_lainnya_dirawat'],
            'nakes_lainnya_isoman' => $data['nakes_lainnya_isoman'],
            'nakes_lainnya_sembuh' => $data['nakes_lainnya_sembuh'],
            'perawat_dirawat' => $data['perawat_dirawat'],
            'perawat_isoman' => $data['perawat_isoman'],
            'perawat_sembuh' => $data['perawat_sembuh'],
            'butuh_sinkron_ulang' => 1,
            'status_sinkron' => 0,
            'user_id' => auth()->user()->id,
            'nama_user' => auth()->user()->name
        ];

        $nakesTerinfeksi = NakesTerinfeksi::updateOrCreate([
            'tanggal' => $request->tanggal
        ],$saveData);

        if($request->isKirim == "true") {
            $api = new ApiPcrNakesController;
            $response = $api->simpanNakesTerinfeksi($nakesTerinfeksi);
            return JsonResponseController::jsonDataWithIcon($nakesTerinfeksi, $response, 'success');
        }
        return JsonResponseController::jsonDataWithIcon($nakesTerinfeksi, 'Berhasil Simpan!', 'success');
    }

    public function send(Request $request)
    {
        $nakesTerinfeksi = NakesTerinfeksi::find($request->id);
        $api = new ApiPcrNakesController;
        $response = $api->simpanNakesTerinfeksi($nakesTerinfeksi);
        return JsonResponseController::jsonDataWithIcon($nakesTerinfeksi, $response, 'success');
    }
}
