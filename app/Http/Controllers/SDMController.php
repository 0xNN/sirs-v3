<?php

namespace App\Http\Controllers;

use App\Models\Paramedic;
use App\Models\SDM;
use App\Models\Specialty;
use App\Models\SysGeneralCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SDMController extends Controller
{
    public function index(Request $request)
    {
        $api = new ApiSdmController;
        $dataSdm = null;
        try {
            $dataSdm = $api->getSdm();
        } catch (Exception $e) {
        }
        $count = SDM::count();

        if($count == 0) {
            if($dataSdm != null) {
                foreach($dataSdm as $data) {
                    SDM::updateOrCreate([
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
    
        $sdm = SDM::all();
        if($request->ajax()) {
            return datatables()
                ->of($sdm)
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
        return view('pages.sdm.index');
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

    public function savesdm(Request $request)
    {
        $api = new ApiSdmController;
        $post = SDM::find($request->id);
        $response = $api->simpanSdm($post);
        if($response == 'Berhasil Simpan!') {
            return JsonResponseController::jsonDataWithIcon($response, $response, 'success');
        }
        if($response == 'Berhasil Update!') {
            return JsonResponseController::jsonDataWithIcon($response, $response, 'success');
        }
        return JsonResponseController::jsonDataWithIcon($response, $response, 'error');
    }

    public function create(Request $request)
    {
        $paramedicType = SysGeneralCode::where('ParentID', 'X0055')->get();

        $paramedic = Paramedic::select(
                            'GCParamedicType', 
                            DB::raw('count(*) as Jumlah'),
                            // 'ParamedicID',
                            'SpecialtyCode')
                            ->groupBy('SpecialtyCode')
                            ->get();

                            
        $specialty = Specialty::all();
        $gc = new GeneralCodeController;

        if($request->ajax()) {
            if($request->tableData == 'paramedictype') {
                return datatables()
                    ->of($paramedicType)
                    ->addIndexColumn()
                    // ->editColumn('action', function($row) {
                    //     $button = '<div class="btn-group btn-group-sm" role="group">';
                    //     $button .= '<button id="btn-sinkron" data-id="'.$row->id.'" class="btn-sinkron btn btn-sm btn-info"><i class="fas fa-share"></i></button>';
                    //     $button .= '<button id="btn-delete-sinkron" data-id="'.$row->id.'" class="btn-delete-sinkron btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                    //     $button .= '</div>';
    
                    //     return $button;
                    // })
                    ->editColumn('GeneralCodeID', function($row) {
                        return $row->GeneralCodeID;
                    })
                    ->editColumn('GeneralCodeName1', function($row) use ($gc) {
                        return $gc->parseParamedicType($row->GeneralCodeID);
                    })
                    ->escapeColumns([])
                    ->make(true);
            }
            if($request->tableData == 'specialty') {
                return datatables()
                    ->of($specialty)
                    ->addIndexColumn()
                    // ->editColumn('action', function($row) {
                    //     $button = '<div class="btn-group btn-group-sm" role="group">';
                    //     $button .= '<button id="btn-sinkron" data-id="'.$row->id.'" class="btn-sinkron btn btn-sm btn-info"><i class="fas fa-share"></i></button>';
                    //     $button .= '<button id="btn-delete-sinkron" data-id="'.$row->id.'" class="btn-delete-sinkron btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                    //     $button .= '</div>';
    
                    //     return $button;
                    // })
                    ->editColumn('SpecialtyCode', function($row) {
                        return $row->SpecialtyCode;
                    })
                    ->escapeColumns([])
                    ->make(true);
            }
            if($request->tableData == 'paramedic') {
                if($request->hasHeader('SpecialtyCode')) {
                    if($request->header('SpecialtyCode') != null) {
                        $specialty = Specialty::where('SpecialtyCode', $request->header('SpecialtyCode'))->select('SpecialtyCode');
                        $paramedic = Paramedic::select(
                            'GCParamedicType', 
                            DB::raw('count(*) as Jumlah'),
                            // 'ParamedicID',
                            'SpecialtyCode')
                            ->whereIn('SpecialtyCode', $specialty)
                            ->groupBy('SpecialtyCode')
                            ->get();
                    }
                }
                return datatables()
                    ->of($paramedic)
                    ->addIndexColumn()
                    ->editColumn('SpecialtyCode', function($row) {
                        return $this->return($row->specialty);
                    })
                    ->editColumn('GCParamedicType', function($row) use ($gc) {
                        // return $this->return($row->paramedicType);
                        return $gc->parseParamedicType($row->GCParamedicType);
                    })
                    ->escapeColumns([])
                    ->make(true);
            }
        }
        return view('pages.sdm.create');
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
            'SpecialtyName1',
            'GeneralCodeName1'
        ];
    }

    public function classapi(Request $request)
    {
        $search = $request->search;
        $api = new ApiSdmController;
        $data = $api->getReferensiSdm();

        if($search == ''){
            $datas = $data;
        }else{
            foreach($data as $val)
            {
                if(stripos($val->kebutuhan, $search) !== false) {
                    $datas[] = $val;
                }
            }
        }
        $response = array();
        foreach($datas as $val){
            $response[] = array(
                "id"=>$val->id_kebutuhan,
                "text"=>$val->kebutuhan
            );
        }
        return response()->json($response);
    }

    public function getsdm(Request $request)
    {
        $sdm = SDM::where('id_kebutuhan', $request->id_kebutuhan)->first();
        return response()->json($sdm);
    }

    public function store(Request $request)
    {
        $api = new ApiSdmController;
        if($request->hasHeader('IsUpdate')) {
            if($request->header('IsUpdate') == "true") {
                $sdm = SDM::where('id_kebutuhan', $request->kebutuhan)->update([
                    'jumlah_eksisting' => $request->jumlah_eksisting,
                    'jumlah' => $request->jumlah,
                    'jumlah_diterima' => $request->jumlah_diterima,
                    'butuh_sinkron_ulang' => 1,
                    'user_id' => auth()->user()->id
                ]);
                $post = SDM::where('id_kebutuhan', $request->kebutuhan)
                            ->where('butuh_sinkron_ulang', 1)
                            ->first();
                if(auth()->user()->role_id == 1) {
                    $response = $api->simpanSdm($post);
                } 
                if(auth()->user()->role_id == 0) {
                    $response = 'Berhasil Update!';
                }
            }
            if($request->header('IsUpdate') == "false") {
                $sdm = SDM::create([
                    'id_kebutuhan' => $request->kebutuhan,
                    'jumlah_eksisting' => $request->jumlah_eksisting,
                    'jumlah' => $request->jumlah,
                    'jumlah_diterima' => $request->jumlah_diterima,
                    'butuh_sinkron_ulang' => 1,
                    'status_sinkron' => 0,
                    'user_id' => auth()->user()->id,
                    'nama_user' => auth()->user()->name,
                ]);
                $post = SDM::where('id_kebutuhan', $request->kebutuhan)
                            ->whereId($sdm->id)
                            ->first();
                if(auth()->user()->role_id == 1) {
                    $response = $api->simpanSdm($post);
                }
                if(auth()->user()->role_id == 0) {
                    $response = 'Berhasil Simpan!';
                }
            }
        }
        if($response == 'Berhasil Simpan!') {
            return JsonResponseController::jsonDataWithIcon($response, $response, 'success');
        }
        if($response == 'Berhasil Update!') {
            return JsonResponseController::jsonDataWithIcon($response, $response, 'success');
        }
        return JsonResponseController::jsonDataWithIcon($response, $response, 'error');
    }
}
