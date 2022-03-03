<?php

namespace App\Http\Controllers;

use App\Models\Alkes;
use Exception;
use Illuminate\Http\Request;

class AlkesController extends Controller
{
    public function index(Request $request)
    {
        $api = new ApiAlkesController;
        $dataAlkes = null;
        try {
            $dataAlkes = $api->getAlkes();
        } catch (Exception $e) {
        }
        $count = Alkes::count();

        if($count == 0) {
            if($dataAlkes != null) {
                foreach($dataAlkes as $data) {
                    Alkes::updateOrCreate([
                        'id_kebutuhan' => $data->id_kebutuhan,
                    ],[
                        'kebutuhan' => $data->kebutuhan,
                        'jumlah_eksisting' => $data->jumlah_eksisting,
                        'jumlah' => $data->jumlah,
                        'jumlah_diterima' => $data->jumlah_diterima,
                        'tglupdate' => $data->tglupdate,
                        'butuh_sinkron_ulang' => 0,
                        'status_sinkron' => 1,
                        'user_id' => auth()->user()->id,
                        'nama_user' => 'API'
                    ]);
                }
            }
        }
    
        $alkes = Alkes::all();
        if($request->ajax()) {
            return datatables()
                ->of($alkes)
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
        return view('pages.alkes.index');
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
        
    }
}
