<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\ClassM;
use App\Models\DepartmentServiceUnit;
use App\Models\Ruangan;
use App\Models\ServiceRoom;
use App\Models\ServiceUnit;
use App\Models\SysGeneralCode;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function covid(Request $request)
    {
        $api = new ApiSiranapController;
        
        $cek = Ruangan::all();
        $data = $api->getTT();
        if($cek->count() == 0) {
            foreach($data as $item) {
                Ruangan::updateOrCreate([
                    'id_tt' => $item->id_tt,
                    'tt' => $item->tt,
                    // 'id_t_tt' => $item->id_t_tt,
                    'ruang' => $item->ruang,
                ],[
                    'id_t_tt' => $item->id_t_tt,
                    'kode_siranap' => $item->kode_siranap,
                    'jumlah_ruang' => $item->jumlah_ruang,
                    'jumlah' => $item->jumlah,
                    'terpakai' => $item->terpakai,
                    'terpakai_suspek' => $item->terpakai_suspek,
                    'terpakai_konfirmasi' => $item->terpakai_konfirmasi,
                    'antrian' => $item->antrian,
                    'prepare' => $item->prepare,
                    'prepare_plan' => $item->prepare_plan,
                    'kosong' => $item->kosong,
                    'covid' => $item->covid,

                    'butuh_sinkron_ulang' => 0,
                    'status_sinkron' => 1,
                    'user_id' => auth()->user()->id,
                    'nama_user' => 'API'
                ]);
            }
        }

        $countData = count($api->getTT());
        $notifMessageButuh = '';
        $notifMessage = '';
        if($countData < $cek->count()) {
            $belumSinkron = $cek->count() - $countData;
            $ruangStatusButuh = Ruangan::where('status_sinkron', 1)
                                    ->where('butuh_sinkron_ulang', 1)
                                    ->where('covid', 1)
                                    ->count();

            if($ruangStatusButuh > 0) {
                $notifMessageButuh = 'Terdapat '.$ruangStatusButuh.' Data Ruangan yang perlu Dikirim Ulang!';
            }
            $notifMessage = 'Terdapat '.$belumSinkron.' Data Ruangan yang belum Dikirim!';
        }

        if($countData == $cek->count()) {
            $ruangStatus = Ruangan::where('status_sinkron', 0)
                                    ->count();
            if($ruangStatus > 0) {
                foreach($data as $item) {
                    Ruangan::updateOrCreate([
                        'id_tt' => $item->id_tt,
                        'tt' => $item->tt,
                        // 'id_t_tt' => $item->id_t_tt
                        'ruang' => $item->ruang
                    ],[
                        'id_t_tt' => $item->id_t_tt,
                        'kode_siranap' => $item->kode_siranap,
                        'jumlah_ruang' => $item->jumlah_ruang,
                        'jumlah' => $item->jumlah,
                        'terpakai' => $item->terpakai,
                        'terpakai_suspek' => $item->terpakai_suspek,
                        'terpakai_konfirmasi' => $item->terpakai_konfirmasi,
                        'antrian' => $item->antrian,
                        'prepare' => $item->prepare,
                        'prepare_plan' => $item->prepare_plan,
                        'kosong' => $item->kosong,
                        'covid' => $item->covid,
    
                        'butuh_sinkron_ulang' => 0,
                        'status_sinkron' => 1,
                        'user_id' => auth()->user()->id,
                        'nama_user' => 'API'
                    ]);
                }
            }
        }
        $akses_id = auth()->user()->akses_id;
        $ruangan = Ruangan::whereCovid($akses_id)
                            ->orderByDesc('updated_at')
                            ->get();
                            
        if($request->ajax()) {
            return datatables()
                ->of($ruangan)
                ->addIndexColumn()
                ->editColumn('action', function($row) {
                    if(auth()->user()->role_id == 1) {
                        $button = '<div class="btn-group btn-group-sm" role="group">';
                        $button .= '<button id="btn-sinkron" data-id="'.$row->id.'" class="btn-sinkron btn btn-sm btn-info"><i class="fas fa-share"></i></button>';
                        $button .= '<button id="btn-delete-sinkron" data-id="'.$row->id.'" class="btn-delete-sinkron btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
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
        return view('pages.ruangan.covid.index', compact(
            'notifMessageButuh',
            'notifMessage'
        ));
    }

    public function noncovid(Request $request)
    {
        $api = new ApiSiranapController;
        
        $cek = Ruangan::all();
        $data = $api->getTT();
        if($cek->count() == 0) {
            foreach($data as $item) {
                Ruangan::updateOrCreate([
                    'id_tt' => $item->id_tt,
                    'tt' => $item->tt,
                    // 'id_t_tt' => $item->id_t_tt,
                    'ruang' => $item->ruang
                ],[
                    'id_t_tt' => $item->id_t_tt,
                    'kode_siranap' => $item->kode_siranap,
                    'jumlah_ruang' => $item->jumlah_ruang,
                    'jumlah' => $item->jumlah,
                    'terpakai' => $item->terpakai,
                    'terpakai_suspek' => $item->terpakai_suspek,
                    'terpakai_konfirmasi' => $item->terpakai_konfirmasi,
                    'antrian' => $item->antrian,
                    'prepare' => $item->prepare,
                    'prepare_plan' => $item->prepare_plan,
                    'kosong' => $item->kosong,
                    'covid' => $item->covid,

                    'butuh_sinkron_ulang' => 0,
                    'status_sinkron' => 1,
                    'user_id' => auth()->user()->id,
                    'nama_user' => 'API'
                ]);
            }
        }

        $countData = count($api->getTT());
        $notifMessageButuh = '';
        $notifMessage = '';
        if($countData < $cek->count()) {
            $belumSinkron = $cek->count() - $countData;
            $ruangStatusButuh = Ruangan::where('status_sinkron', 1)
                                    ->where('butuh_sinkron_ulang', 1)
                                    ->count();

            if($ruangStatusButuh > 0) {
                $notifMessageButuh = 'Terdapat '.$ruangStatusButuh.' Data Ruangan yang perlu Dikirim Ulang!';
            }
            $notifMessage = 'Terdapat '.$belumSinkron.' Data Ruangan yang belum Dikirim!';
        }

        if($countData == $cek->count()) {
            $ruangStatus = Ruangan::where('status_sinkron', 0)
                                    ->count();
            if($ruangStatus > 0) {
                foreach($data as $item) {
                    Ruangan::updateOrCreate([
                        'id_tt' => $item->id_tt,
                        'tt' => $item->tt,
                        // 'id_t_tt' => $item->id_t_tt
                        'ruang' => $item->ruang
                    ],[
                        'id_t_tt' => $item->id_t_tt,
                        'kode_siranap' => $item->kode_siranap,
                        'jumlah_ruang' => $item->jumlah_ruang,
                        'jumlah' => $item->jumlah,
                        'terpakai' => $item->terpakai,
                        'terpakai_suspek' => $item->terpakai_suspek,
                        'terpakai_konfirmasi' => $item->terpakai_konfirmasi,
                        'antrian' => $item->antrian,
                        'prepare' => $item->prepare,
                        'prepare_plan' => $item->prepare_plan,
                        'kosong' => $item->kosong,
                        'covid' => $item->covid,
    
                        'butuh_sinkron_ulang' => 0,
                        'status_sinkron' => 1,
                        'user_id' => auth()->user()->id,
                        'nama_user' => 'API'
                    ]);
                }
            }
        }
        $akses_id = auth()->user()->akses_id;
        $ruangan = Ruangan::whereCovid($akses_id)
                            ->orderByDesc('updated_at')
                            ->get();
                            
        if($request->ajax()) {
            return datatables()
                ->of($ruangan)
                ->addIndexColumn()
                ->editColumn('action', function($row) {
                    if(auth()->user()->role_id == 1) {
                        $button = '<div class="btn-group btn-group-sm" role="group">';
                        $button .= '<button id="btn-sinkron" data-id="'.$row->id.'" class="btn-sinkron btn btn-sm btn-info"><i class="fas fa-share"></i></button>';
                        $button .= '<button id="btn-delete-sinkron" data-id="'.$row->id.'" class="btn-delete-sinkron btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
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
        return view('pages.ruangan.noncovid.index', compact(
            'notifMessageButuh',
            'notifMessage'
        ));
    }

    public function index(Request $request)
    {
        $totalCovid = Ruangan::whereCovid(1)->count();
        $totalNonCovid = Ruangan::whereCovid(0)->count();
        return view('pages.ruangan.index', compact(
            'totalCovid',
            'totalNonCovid'
        ));
    }
    
    public function destroy(Request $request)
    {
        $api = new ApiSiranapController;
        $ruangan = Ruangan::find($request->id);
        $response = $api->hapusTT($ruangan);
        return JsonResponseController::jsonDataWithIcon(null, $response, 'success');
    }

    public function store(Request $request)
    {
        $api = new ApiSiranapController;
        $ruangan = Ruangan::find($request->id);
        $response = $api->simpanTT($ruangan);
        return JsonResponseController::jsonDataWithIcon(null, $response, 'success');
    }

    public function savebed(Request $request)
    {
        $api = new ApiSiranapController;
        $referensi = $api->getReferensiTT();

        $akses_id = auth()->user()->akses_id;

        if($request->hasHeader('IsUpdate')) {
            $IsUpdate = $request->header('IsUpdate');
        }
        if($IsUpdate == "true") {
            // Update Data Ruangan
            $id = $request->RuanganLama;
            $ruangan = Ruangan::where('id', $id)
            ->update([
                'ruang' => $request->RoomName,
                'jumlah_ruang' => $request->JumlahRuang,
                'jumlah' => $request->TotalBed,
                'terpakai' => $request->O0116,
                'terpakai_suspek' => auth()->user()->akses_id == 1 ? $request->terpakai_suspek: 0,
                'terpakai_konfirmasi' => auth()->user()->akses_id == 1 ? $request->terpakai_konfirmasi: 0,
                'antrian' => 0,
                'prepare' => 0,
                'prepare_plan' => 0,
                'kosong' => $request->TotalBed - $request->O0116,
                'covid' => $akses_id,
                'ClassCode' => $request->header('ClassCode'),
                'ServiceUnitID' => $request->header('ServiceUnitID'),
                'RoomID' => $request->header('RoomID'),
                'butuh_sinkron_ulang' => 1,
                'user_id' => auth()->user()->id,
                'nama_user' => auth()->user()->name,
            ]);
            $message = 'Berhasil diupdate!';
        } else {
            // Simpan Data Ruangan Baru
            $tt = "";
            foreach($referensi as $val)
            {
                if($val->kode_tt == $request->ClassApi) {
                    $tt = $val->nama_tt;
                    break;
                }
            }
            $ruangan = Ruangan::create([
                'id_tt' => $request->ClassApi,
                'tt' => $tt,
                'ruang' => $request->RoomName,
                'jumlah_ruang' => $request->JumlahRuang,
                'jumlah' => $request->TotalBed,
                'terpakai' => $request->O0116,
                'terpakai_suspek' => 0,
                'terpakai_konfirmasi' => 0,
                'antrian' => 0,
                'prepare' => 0,
                'prepare_plan' => null,
                'kosong' => $request->TotalBed - $request->O0116,
                'covid' => $akses_id,
                'ClassCode' => $request->header('ClassCode'),
                'ServiceUnitID' => $request->header('ServiceUnitID'),
                'RoomID' => $request->header('RoomID'),
                'butuh_sinkron_ulang' => 1,
                'status_sinkron' => 0,
                'user_id' => auth()->user()->id,
                'nama_user' => auth()->user()->name,
            ]);
            $message = 'Berhasil disimpan!';
        }
        return JsonResponseController::jsonDataWithIcon($ruangan, $message, 'success');
    }

    public function create(Request $request)
    {
        // $data = ClassM::join('bed', function($join) {
        //     $join->on('class.ClassCode', '=', 'bed.ClassCode');
        // })
        // ->join('serviceroom', function($join) {
        //     $join->on('bed.RoomID', '=', 'serviceroom.RoomID');
        // })
        // ->join('departmentserviceunit', function($join) {
        //     $join->on('bed.ServiceUnitID', '=', 'departmentserviceunit.ServiceUnitID');
        // })
        // ->join('serviceunit', function($join) {
        //     $join->on('departmentserviceunit.ServiceUnitCode', '=', 'serviceunit.ServiceUnitCode');
        // })
        // ->orderByDesc('bed.LastUpdatedDateTime')
        // ->limit(10)
        // ->get();
        
        // dd($data);
        if($request->ajax()) {
            if($request->tableData == 'class') {
                $data = ClassM::all();
                return datatables()
                    ->of($data)
                    ->addIndexColumn()
                    ->editColumn('action', function($row) {
                        $button = '<div class="btn-group btn-group-sm" role="group">';
                        $button .= '<button id="btn-sinkron" data-id="'.$row->id.'" class="btn-sinkron btn btn-sm btn-info"><i class="fas fa-share"></i></button>';
                        $button .= '</div>';
    
                        return $button;
                    })
                    ->editColumn('IsActive', function($row) {
                        return $this->spanActive($row->IsActive);
                    })
                    ->escapeColumns([])
                    ->make(true);
            }
            if($request->tableData == 'serviceunit') {
                if($request->hasHeader('ClassCode')) {
                    if($request->header('ClassCode') == null) {
                        $bed = Bed::distinct()->select('ServiceUnitID');
                    } else {
                        $bed = Bed::where('ClassCode', $request->header('ClassCode'))
                        ->distinct()
                        ->select('ServiceUnitID');
                    }
                }
                $data = DepartmentServiceUnit::whereIn('ServiceUnitID', $bed)
                        ->orderByDesc('LastUpdatedDateTime')
                        ->get();

                return datatables()
                    ->of($data)
                    ->addIndexColumn()
                    ->editColumn('ServiceUnitName', function($row) {
                        return $this->return($row->serviceUnit);
                    })
                    ->editColumn('ShortName', function($row) {
                        return $this->return($row->serviceUnit).'/'.$row->serviceUnit->Initial;
                    })
                    ->editColumn('IsActive', function($row) {
                        return $this->spanActive($this->return($row->serviceUnit));
                    })
                    ->escapeColumns([])
                    ->make(true);
            }
            if($request->tableData == 'room') {
                if($request->hasHeader('ClassCode') && $request->hasHeader('ServiceUnitID')) {
                    if($request->header('ServiceUnitID') == null && $request->header('ClassCode') != null) {
                        $bed = Bed::where('ClassCode', $request->header('ClassCode'))
                        ->distinct()
                        ->select('RoomID');
                    }
                    if($request->header('ClassCode') == null && $request->header('ServiceUnitID') != null) {
                        $bed = Bed::where('ServiceUnitID', $request->header('ServiceUnitID'))
                        ->distinct()
                        ->select('RoomID');
                    }
                    if($request->header('ClassCode') == null && $request->header('ServiceUnitID') == null) {
                        $bed = Bed::distinct()
                        ->select('RoomID');
                    }
                    if($request->header('ClassCode') != null && $request->header('ServiceUnitID') != null) {
                        $bed = Bed::where('ServiceUnitID', $request->header('ServiceUnitID'))
                                    ->where('ClassCode', $request->header('ClassCode'))
                                    ->distinct()
                                    ->select('RoomID');
                    }
                    $data = ServiceRoom::orderByDesc('LastUpdatedDateTime')
                            ->whereIn('RoomID', $bed)
                            ->get();
                }
                return datatables()
                    ->of($data)
                    ->addIndexColumn()
                    ->editColumn('IsActive', function($row) {
                        return $this->spanActive($row->IsActive);
                    })
                    ->escapeColumns([])
                    ->make(true);
            }
        }
        return view('pages.ruangan.create');
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
            'GeneralCodeName1',
            'ServiceUnitID',
            'ShortName',
            'ServiceUnitName',
            // 'Initial',
            'IsActive'
        ];
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

    public function serviceunit(Request $request)
    {
        $search = $request->search;
    }

    public function classapi(Request $request)
    {
        $search = $request->search;
        $api = new ApiSiranapController;
        $data = $api->getReferensiTT();
        $akses_id = auth()->user()->akses_id;

        $ruangan = Ruangan::where('covid', $akses_id)->get();
        
        $datae = array();
        foreach($data as $val) {
            foreach($ruangan as $ruang) {
                if($val->kode_tt == $ruang->id_tt) {
                    $datae[] = $val;
                }
            }
        }

        if($search == ''){
            $datas = $datae;
        }else{
            foreach($datae as $val)
            {
                if(stripos($val->nama_tt, $search) !== false) {
                    $datas[] = $val;
                }
            }
        }
        $response = array();
        foreach($datas as $val){
            $response[] = array(
                "id"=>$val->kode_tt,
                "text"=>$val->nama_tt
            );
        }
        return response()->json($response);
    }

    public function ruanganlama(Request $request)
    {
        $search = $request->search;
        
        if($search == ''){
            $datas = Ruangan::where('covid', auth()->user()->akses_id)->get();
        }else{
            $datas = Ruangan::where('covid', auth()->user()->akses_id)
                            ->where(function($query) use ($search) {
                                $query->where('ruang', 'like', '%'.$search.'%')
                                    ->orWhere('tt', 'like', '%'.$search.'%');
                            })
                            ->get();
        }
        $response = array();
        foreach($datas as $val){
            $response[] = array(
                "id"=>$val->id,
                "text"=>$val->tt.'-'.$val->ruang
            );
        }
        return response()->json($response);
    }

    public function cek_bed(Request $request)
    {
        if($request->ClassCode == null || $request->ServiceUnitID == null) {
            return JsonResponseController::jsonDataWithIcon(null, 'Lengkapi ClassCode, Service Unit, dan Room dahulu!', 'error');
        }

        if($request->RoomID == null) {
            $countTotal = Bed::where('ServiceUnitID', $request->ServiceUnitID)
                        ->where('ClassCode', $request->ClassCode)
                        ->count();
        } else {
            $countTotal = Bed::where('RoomID', $request->RoomID)
                        ->where('ServiceUnitID', $request->ServiceUnitID)
                        ->where('ClassCode', $request->ClassCode)
                        ->count();
        }

        $jumlahRuang = Bed::where('ServiceUnitID', $request->ServiceUnitID)
        ->where('ClassCode', $request->ClassCode)
        ->distinct('BedID')
        ->count();

        $room = ServiceRoom::where('RoomID', $request->RoomID)->first();
        $serviceunit = DepartmentServiceUnit::where('ServiceUnitID', $request->ServiceUnitID)->first();
        $class = ClassM::where('ClassCode', $request->ClassCode)->first();
        
        $count = $this->countStatusBed($request);

        $data = [
            'total_bed' => $countTotal,
            'room' => $room == null ? "": $room,
            'serviceunit' => $serviceunit->serviceUnit,
            'class' => $class,
            'status' => $count,
            'jumlah_ruang' => $jumlahRuang
        ];

        return JsonResponseController::jsonDataWithIcon($data, 'Data ditemukan!', 'success');
    }

    public function countStatusBed($request)
    {
        $bedStatus = SysGeneralCode::where('ParentID', '0116')->get();
        $data = array();
        foreach($bedStatus as $status) {
            $parse = $this->parseBed($status->GeneralCodeID);
            if($request->RoomID == null) {
                $data[$parse] = Bed::where('ServiceUnitID', $request->ServiceUnitID)
                ->where('ClassCode', $request->ClassCode)
                ->where('GCBedStatus', $status->GeneralCodeID)
                ->count();
            } else {
                $data[$parse] = Bed::where('RoomID', $request->RoomID)
                ->where('ServiceUnitID', $request->ServiceUnitID)
                ->where('ClassCode', $request->ClassCode)
                ->where('GCBedStatus', $status->GeneralCodeID)
                ->count();
            }
        }
        return $data;
    }

    public function parseBed($data)
    {
        $model = [
            '0116^B' => 'B0116',
            '0116^C' => 'C0116',
            '0116^O' => 'O0116',
            '0116^P' => 'P0116',
            '0116^R' => 'R0116',
            '0116^W' => 'W0116',
            '0116^X' => 'X0116'
        ];
        return $model[$data];
    }

}
