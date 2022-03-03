<?php

namespace App\Http\Controllers;

use App\Models\PcrNakes;
use Exception;
use Illuminate\Http\Request;

class PcrNakesController extends Controller
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
        $dataPcrNakes = null;
        try {
            $dataPcrNakes = $api->getPcrNakes($tgl);
        } catch (Exception $e) {
        }
        $count = PcrNakes::count();

        // dd($dataPcrNakes);
        if($dataPcrNakes != '202') {
            // if($count == 0) {
                if($dataPcrNakes != null) {
                    foreach($dataPcrNakes as $data) {
                        PcrNakes::updateOrCreate([
                            'tanggal' => $data->tanggal
                        ],[
                            'tanggal' => $data->tanggal,
                            'jumlah_tenaga_dokter_umum' => $data->jumlah_tenaga_dokter_umum,
                            'sudah_periksa_dokter_umum' => $data->sudah_periksa_dokter_umum,
                            'hasil_pcr_dokter_umum' => $data->hasil_pcr_dokter_umum,
                            'jumlah_tenaga_dokter_spesialis' => $data->jumlah_tenaga_dokter_spesialis,
                            'sudah_periksa_dokter_spesialis' => $data->sudah_periksa_dokter_spesialis,
                            'hasil_pcr_dokter_spesialis' => $data->hasil_pcr_dokter_spesialis,
                            'jumlah_tenaga_dokter_gigi' => $data->jumlah_tenaga_dokter_gigi,
                            'sudah_periksa_dokter_gigi' => $data->sudah_periksa_dokter_gigi,
                            'hasil_pcr_dokter_gigi' => $data->hasil_pcr_dokter_gigi,
                            'jumlah_tenaga_residen' => $data->jumlah_tenaga_residen,
                            'sudah_periksa_residen' => $data->sudah_periksa_residen,
                            'hasil_pcr_residen' => $data->hasil_pcr_residen,
                            'jumlah_tenaga_perawat' => $data->jumlah_tenaga_perawat,
                            'sudah_periksa_perawat' => $data->sudah_periksa_perawat,
                            'hasil_pcr_perawat' => $data->hasil_pcr_perawat,
                            'jumlah_tenaga_bidan' => $data->jumlah_tenaga_bidan,
                            'sudah_periksa_bidan' => $data->sudah_periksa_bidan,
                            'hasil_pcr_bidan' => $data->hasil_pcr_bidan,
                            'jumlah_tenaga_apoteker' => $data->jumlah_tenaga_apoteker,
                            'sudah_periksa_apoteker' => $data->sudah_periksa_apoteker,
                            'hasil_pcr_apoteker' => $data->hasil_pcr_apoteker,
                            'jumlah_tenaga_radiografer' => $data->jumlah_tenaga_radiografer,
                            'sudah_periksa_radiografer' => $data->sudah_periksa_radiografer,
                            'hasil_pcr_radiografer' => $data->hasil_pcr_radiografer,
                            'jumlah_tenaga_analis_lab' => $data->jumlah_tenaga_analis_lab,
                            'sudah_periksa_analis_lab' => $data->sudah_periksa_analis_lab,
                            'hasil_pcr_analis_lab' => $data->hasil_pcr_analis_lab,
                            'jumlah_tenaga_co_ass' => $data->jumlah_tenaga_co_ass,
                            'sudah_periksa_co_ass' => $data->sudah_periksa_co_ass,
                            'hasil_pcr_co_ass' => $data->hasil_pcr_co_ass,
                            'jumlah_tenaga_internship' => $data->jumlah_tenaga_internship,
                            'sudah_periksa_internship' => $data->sudah_periksa_internship,
                            'hasil_pcr_internship' => $data->hasil_pcr_internship,
                            'jumlah_tenaga_nakes_lainnya' => $data->jumlah_tenaga_nakes_lainnya,
                            'sudah_periksa_nakes_lainnya' => $data->sudah_periksa_nakes_lainnya,
                            'hasil_pcr_nakes_lainnya' => $data->hasil_pcr_nakes_lainnya,
                            'rekap_jumlah_tenaga' => $data->rekap_jumlah_tenaga,
                            'rekap_jumlah_sudah_diperiksa' => $data->rekap_jumlah_sudah_diperiksa,
                            'rekap_jumlah_hasil_pcr' => $data->rekap_jumlah_hasil_pcr,
                            'tgllapor' => $data->tgllapor,
    
                            'butuh_sinkron_ulang' => 0,
                            'status_sinkron' => 1,
                            'user_id' => auth()->user()->id,
                            'nama_user' => 'API'
                        ]);
                    }
                }
            // }
        }
    
        $pcrNakes = PcrNakes::all();
        if($request->ajax()) {
            return datatables()
                ->of($pcrNakes)
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
        return view('pages.pcrnakes.index');
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
        return view('pages.pcrnakes.create');
    }

    public function store(Request $request)
    {
        $data = $request->data;
        $saveData = [
            'tanggal' => $request->tanggal,
            'jumlah_tenaga_dokter_umum' => $data['jumlah_tenaga_dokter_umum'],
            'sudah_periksa_dokter_umum' => $data['sudah_periksa_dokter_umum'],
            'hasil_pcr_dokter_umum' => $data['hasil_pcr_dokter_umum'],
            'jumlah_tenaga_dokter_spesialis' => $data['jumlah_tenaga_dokter_spesialis'],
            'sudah_periksa_dokter_spesialis' => $data['sudah_periksa_dokter_spesialis'],
            'hasil_pcr_dokter_spesialis' => $data['hasil_pcr_dokter_spesialis'],
            'jumlah_tenaga_dokter_gigi' => $data['jumlah_tenaga_dokter_gigi'],
            'sudah_periksa_dokter_gigi' => $data['sudah_periksa_dokter_gigi'],
            'hasil_pcr_dokter_gigi' => $data['hasil_pcr_dokter_gigi'],
            'jumlah_tenaga_residen' => $data['jumlah_tenaga_residen'],
            'sudah_periksa_residen' => $data['sudah_periksa_residen'],
            'hasil_pcr_residen' => $data['hasil_pcr_residen'],
            'jumlah_tenaga_perawat' => $data['jumlah_tenaga_perawat'],
            'sudah_periksa_perawat' => $data['sudah_periksa_perawat'],
            'hasil_pcr_perawat' => $data['hasil_pcr_perawat'],
            'jumlah_tenaga_bidan' => $data['jumlah_tenaga_bidan'],
            'sudah_periksa_bidan' => $data['sudah_periksa_bidan'],
            'hasil_pcr_bidan' => $data['hasil_pcr_bidan'],
            'jumlah_tenaga_apoteker' => $data['jumlah_tenaga_apoteker'],
            'sudah_periksa_apoteker' => $data['sudah_periksa_apoteker'],
            'hasil_pcr_apoteker' => $data['hasil_pcr_apoteker'],
            'jumlah_tenaga_radiografer' => $data['jumlah_tenaga_radiografer'],
            'sudah_periksa_radiografer' => $data['sudah_periksa_radiografer'],
            'hasil_pcr_radiografer' => $data['hasil_pcr_radiografer'],
            'jumlah_tenaga_analis_lab' => $data['jumlah_tenaga_analis_lab'],
            'sudah_periksa_analis_lab' => $data['sudah_periksa_analis_lab'],
            'hasil_pcr_analis_lab' => $data['hasil_pcr_analis_lab'],
            'jumlah_tenaga_co_ass' => $data['jumlah_tenaga_co_ass'],
            'sudah_periksa_co_ass' => $data['sudah_periksa_co_ass'],
            'hasil_pcr_co_ass' => $data['hasil_pcr_co_ass'],
            'jumlah_tenaga_internship' => $data['jumlah_tenaga_internship'],
            'sudah_periksa_internship' => $data['sudah_periksa_internship'],
            'hasil_pcr_internship' => $data['hasil_pcr_internship'],
            'jumlah_tenaga_nakes_lainnya' => $data['jumlah_tenaga_nakes_lainnya'],
            'sudah_periksa_nakes_lainnya' => $data['sudah_periksa_nakes_lainnya'],
            'hasil_pcr_nakes_lainnya' => $data['hasil_pcr_nakes_lainnya'],
            'rekap_jumlah_tenaga' => $data['rekap_jumlah_tenaga'],
            'rekap_jumlah_sudah_diperiksa' => $data['rekap_jumlah_sudah_diperiksa'],
            'rekap_jumlah_hasil_pcr' => $data['rekap_jumlah_hasil_pcr'],
            'tgllapor' => date('Y-m-d H:i:s'),
            'butuh_sinkron_ulang' => 1,
            'status_sinkron' => 0,
            'user_id' => auth()->user()->id,
            'nama_user' => auth()->user()->name
        ];

        $pcrNakes = PcrNakes::updateOrCreate([
            'tanggal' => $request->tanggal
        ],$saveData);

        if($request->isKirim == "true") {
            $api = new ApiPcrNakesController;
            $response = $api->simpanPcrNakes($pcrNakes);
            return JsonResponseController::jsonDataWithIcon($pcrNakes, $response, 'success');
        }
        return JsonResponseController::jsonDataWithIcon($pcrNakes, 'Berhasil Simpan!', 'success');
    }

    public function send(Request $request)
    {
        $pcrNakes = PcrNakes::find($request->id);
        $api = new ApiPcrNakesController;
        $response = $api->simpanPcrNakes($pcrNakes);
        return JsonResponseController::jsonDataWithIcon($pcrNakes, $response, 'success');
    }
}
