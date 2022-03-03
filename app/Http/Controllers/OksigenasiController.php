<?php

namespace App\Http\Controllers;

use App\Models\Oksigenasi;
use Exception;
use Illuminate\Http\Request;

class OksigenasiController extends Controller
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
        $dataOksigenasi = null;
        try {
            $dataOksigenasi = $api->getOksigenasi($tgl);
        } catch (Exception $e) {
        }
        $count = Oksigenasi::count();

        // dd($dataPcrNakes);
        if($dataOksigenasi != '202') {
            // if($count == 0) {
                if($dataOksigenasi != null) {
                    foreach($dataOksigenasi as $data) {
                        Oksigenasi::updateOrCreate([
                            'tanggal' => $data->tanggal
                        ],[
                            'tanggal' => $data->tanggal,
                            'p_cair' => $data->p_cair,
                            'p_tabung_kecil' => $data->p_tabung_kecil,
                            'p_tabung_sedang' => $data->p_tabung_sedang,
                            'p_tabung_besar' => $data->p_tabung_besar,
                            'k_isi_cair' => $data->k_isi_cair,
                            'k_isi_tabung_kecil' => $data->k_isi_tabung_kecil,
                            'k_isi_tabung_sedang' => $data->k_isi_tabung_sedang,
                            'k_isi_tabung_besar' => $data->k_isi_tabung_besar,
    
                            'butuh_sinkron_ulang' => 0,
                            'status_sinkron' => 1,
                            'user_id' => auth()->user()->id,
                            'nama_user' => 'API'
                        ]);
                    }
                }
            // }
        }
    
        $oksigenasi = Oksigenasi::all();
        if($request->ajax()) {
            return datatables()
                ->of($oksigenasi)
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
        return view('pages.oksigenasi.index');
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
        return view('pages.oksigenasi.create');
    }

    public function send(Request $request)
    {
        $oksigenasi = Oksigenasi::find($request->id);
        $api = new ApiPcrNakesController;
        $response = $api->simpanOksigenasi($oksigenasi);
        return JsonResponseController::jsonDataWithIcon($oksigenasi, $response, 'success');
    }

    public function store(Request $request)
    {
        $p_cair = $this->konversi($request->p_cair, $request->satuan_p_cair);
        $k_isi_cair = $this->konversi($request->k_isi_cair, $request->satuan_k_isi_cair);

        $saveData = [
            'tanggal' => $request->tanggal,
            'p_cair' => $p_cair,
            'p_tabung_kecil' => $request->p_tabung_kecil,
            'p_tabung_sedang' => $request->p_tabung_sedang,
            'p_tabung_besar' => $request->p_tabung_besar,
            'k_isi_cair' => $k_isi_cair,
            'k_isi_tabung_kecil' => $request->k_isi_tabung_kecil,
            'k_isi_tabung_sedang' => $request->k_isi_tabung_sedang,
            'k_isi_tabung_besar' => $request->k_isi_tabung_besar,

            'butuh_sinkron_ulang' => 1,
            'status_sinkron' => 0,
            'user_id' => auth()->user()->id,
            'nama_user' => auth()->user()->name
        ];

        $oksigenasi = Oksigenasi::updateOrCreate([
            'tanggal' => $request->tanggal
        ],$saveData);

        return JsonResponseController::jsonDataWithIcon($oksigenasi, 'Berhasil Simpan!', 'success');
    }

    public function konversi($nilai, $satuan)
    {
        $konversi = 0;
        if($satuan == "m3"){
            $konversi = $nilai;
        }else if($satuan == "liter"){
            $konversi = $nilai * 0.897;
        }else if($satuan == "kg"){
            $konversi = $nilai * 0.78;
        }else if($satuan == "ton"){
            $konversi = $nilai * 788.86;
        }else if($satuan == "galon"){
            $konversi = $nilai * 3.04;
        }
        return (string)$konversi;
    }
}
