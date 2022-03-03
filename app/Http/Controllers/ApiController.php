<?php

/**
* Created by Muhammad Sendi Noviansyah
* 20 Januari 2022
* Use to API RS Online V3
*/

namespace App\Http\Controllers;

use App\Models\TokenAccess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function base_url()
    {
        return config('myconfig.api.base_url');
    }

    public function url_sirs()
    {
        return config('myconfig.api.url_sirs');
    }

    public function loginrs()
    {
        $response = Http::post($this->base_url().'rslogin', [
            'kode_rs' => config('myconfig.login.kode_rs'),
            'password' => config('myconfig.login.password')
        ]);
        return json_decode($response->body());
    }

    public function saveToken()
    {
        $newToken = $this->loginrs();
        if($newToken->status == false) {
            return $newToken->message;
            // if($newToken->message == 'page not found') return $newToken->message;
        }
        TokenAccess::truncate();
        TokenAccess::create([
            'token' => $newToken->data->access_token,
            'expired_in' => $newToken->data->expires_in,
            'issued_at' => Carbon::parse($newToken->data->issued_at)->format('Y-m-d H:i:s'),
            'expired_at' => Carbon::parse($newToken->data->expired_at)->format('Y-m-d H:i:s')
        ]);
        return $newToken->data->access_token;
    }

    public function getToken()
    {
        $token_access = TokenAccess::first();
        if($token_access) {
            return $token_access->token;
        }
        return $this->saveToken();
    }

    public function handleExpired()
    {
        return $this->saveToken();
    }

    public function kirimDataVaksinasi($post)
    {
        $this->handleExpired();
        foreach($post as $p) {
            $body = [
                'laporanCovid19Versi3Id' => $p->laporanCovid19VersiId,
                'dosisVaksinId' => $p->dosisVaksinId,
                'jenisVaksinId' => $p->jenisVaksinId
            ];
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->post($this->base_url().'laporancovid19versi3vaksinasi',$body);
        }
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->kirimDataVaksinasi($post);
    }

    public function kirimDataStatusKeluar($post)
    {
        $this->handleExpired();
        $body = [
            'laporanCovid19Versi3Id' => $post->laporanCovid19VersiId,
            'tanggalKeluar' => $post->tanggalKeluar,
            'statusKeluarId' => $post->statusKeluarId,
            'kasusKematianId' => $post->kasusKematianId == -1 ? null: $post->kasusKematianId,
            'penyebabKematianLangsungId' => $post->penyebabKematianLangsungId == -1 ? null: $post->penyebabKematianLangsungId
        ];
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->post($this->base_url().'laporancovid19versi3statuskeluar',$body);
        
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->kirimDataStatusKeluar($post);
    }

    public function kirimDataTerapi($post)
    {
        $this->handleExpired();
        foreach($post as $p) {
            $body = [
                'laporanCovid19Versi3Id' => $p->laporanCovid19VersiId,
                'terapiId' => $p->terapiId,
                'jumlahTerapi' => $p->jumlahTerapi
            ];
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->post($this->base_url().'laporancovid19versi3terapi',$body);
        }
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->kirimDataTerapi($post);
    }

    public function kirimDataKomorbid($post)
    {
        $this->handleExpired();
        foreach($post as $p) {
            $body = [
                'laporanCovid19Versi3Id' => $p->laporanCovid19VersiId,
                'komorbidId' => $p->komorbidId
            ];
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->post($this->base_url().'laporancovid19versi3komorbid',$body);
        }
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->kirimDataKomorbid($post);
    }

    public function kirimDataDiagnosa($post)
    {
        $this->handleExpired();
        foreach($post as $p) {
            $body = [
                "laporanCovid19Versi3Id" => $p->laporanCovid19VersiId,
                "diagnosaLevelId" => $p->diagnosaLevelId,
                "diagnosaId" => $p->diagnosaId
            ];
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->post($this->base_url().'laporancovid19versi3diagnosa',$body);
        }
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->kirimDataDiagnosa($post);
    }

    public function updateDataKomorbid($post)
    {
        $this->handleExpired();
        foreach($post as $p) {
            $body = [
                "komorbidId" => $p->komorbidId
            ];
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->patch($this->base_url().'laporancovid19versi3komorbid/'.$p->id_komorbid,$body);
        }
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->updateDataKomorbid($post);
    }

    public function updateDataTerapi($post)
    {
        $this->handleExpired();
        foreach($post as $p) {
            $body = [
                "terapiId" => $p->terapiId,
                "jumlahTerapi" => $p->jumlahTerapi
            ];
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->patch($this->base_url().'laporancovid19versi3terapi/'.$p->id_terapi,$body);
        }
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->updateDataTerapi($post);
    }

    public function updateDataVaksinasi($post)
    {
        $this->handleExpired();
        foreach($post as $p) {
            $body = [
                "dosisVaksinId" => $p->dosisVaksinId,
                "jenisVaksinId" => $p->jenisVaksinId
            ];
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->patch($this->base_url().'laporancovid19versi3vaksinasi/'.$p->laporanCovid19Versi3Id,$body);
        }
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->updateDataVaksinasi($post);
    }

    // public function updateDataStatusKeluar($post)
    // {
    //     $this->handleExpired();
    //     $body = [
    //         'laporanCovid19Versi3Id' => $post->laporanCovid19VersiId,
    //         'tanggalKeluar' => $post->tanggalKeluar,
    //         'statusKeluarId' => $post->statusKeluarId,
    //         'kasusKematianId' => $post->kasusKematianId,
    //         'penyebabKematianLangsungId' => $post->penyebabKematianLangsungId
    //     ];
    //     $response = Http::withHeaders([
    //         'accept' => '*/*',
    //         'Authorization' => 'Bearer '.$this->getToken()
    //     ])->post($this->base_url().'laporancovid19versi3statuskeluar',$body);
        
    //     $data = json_decode($response->body());

    //     if($data->status) return $data;
    //     if($data->status == false) return $data;

    //     $this->handleExpired();
    //     return $this->updateDataStatusKeluar($post);
    // }
    
    public function updateDataDiagnosa($post)
    {
        $this->handleExpired();
        foreach($post as $p) {
            $body = [
                "diagnosaLevelId" => $p->diagnosaLevelId,
                "diagnosaId" => $p->diagnosaId
            ];
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->patch($this->base_url().'laporancovid19versi3diagnosa/'.$p->id_diagnosa,$body);
        }
        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->updateDataDiagnosa($post);
    }

    public function kirimData($post)
    {
        $this->handleExpired();
        $body = [
            "kewarganegaraanId" => $post->kewarganegaraanId,
            "nik" => $post->nik,
            "noPassport" => $post->noPassport,
            "noRM" => $post->noRM,
            "namaLengkapPasien" => $post->namaLengkapPasien,
            "namaInisialPasien" => $post->namaInisialPasien,
            "tanggalLahir" => $post->tanggalLahir,
            "email" => $post->email,
            "noTelp" => $post->noTelp,
            "jenisKelaminId" => $post->jenisKelaminId,
            "asalPasienId" => $post->asalPasienId,
            "domisiliProvinsiId" => $post->domisiliProvinsiId,
            "domisiliKabKotaId" => $post->domisiliKabKotaId,
            "domisiliKecamatanId" => $post->domisiliKecamatanId,
            "pekerjaanId" => $post->pekerjaanId,
            "tanggalMasuk" => $post->tanggalMasuk,
            "jenisPasienId" => $post->jenisPasienId,
            "statusPasienId" => $post->statusPasienId,
            "statusCoInsidenId" => $post->statusCoInsidenId,
            "statusRawatId" => $post->statusRawatId,
            "alatOksigenId" => $post->alatOksigenId,
            "penyintasId" => $post->penyintasId,
            "tanggalOnsetGejala" => $post->tanggalOnsetGejala,
            "kelompokGejalaId" => $post->kelompokGejalaId,
            "gejala" => [
                "demamId" => $post->demamId,
                "batukId" => $post->batukId,
                "pilekId" => $post->pilekId,
                "sakitTenggorokanId" => $post->sakitTenggorokanId,
                "sesakNapasId" => $post->sesakNapasId,
                "lemasId" => $post->lemasId,
                "nyeriOtotId" => $post->nyeriOtotId,
                "mualMuntahId" => $post->mualMuntahId,
                "diareId" => $post->diareId,
                "anosmiaId" => $post->anosmiaId,
                "napasCepatId" => $post->napasCepatId,
                "frekNapas30KaliPerMenitId" => $post->frekNapas30KaliPerMenitId,
                "distresPernapasanBeratId" => $post->distresPernapasanBeratId,
                "lainnyaId" => $post->lainnyaId,
            ]
        ];
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->post($this->base_url().'laporancovid19versi3',$body);

        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->kirimData($post);
    }

    public function updateData($post)
    {
        $this->handleExpired();
        $body = [
            "kewarganegaraanId" => $post->kewarganegaraanId,
            "nik" => $post->nik,
            "noPassport" => $post->noPassport,
            "noRM" => $post->noRM,
            "namaLengkapPasien" => $post->namaLengkapPasien,
            "namaInisialPasien" => $post->namaInisialPasien,
            "tanggalLahir" => $post->tanggalLahir,
            "email" => $post->email,
            "noTelp" => $post->noTelp,
            "jenisKelaminId" => $post->jenisKelaminId,
            "asalPasienId" => $post->asalPasienId,
            "domisiliProvinsiId" => $post->domisiliProvinsiId,
            "domisiliKabKotaId" => $post->domisiliKabKotaId,
            "domisiliKecamatanId" => $post->domisiliKecamatanId,
            "pekerjaanId" => $post->pekerjaanId,
            "tanggalMasuk" => $post->tanggalMasuk,
            "jenisPasienId" => $post->jenisPasienId,
            "statusPasienId" => $post->statusPasienId,
            "statusCoInsidenId" => $post->statusCoInsidenId,
            "statusRawatId" => $post->statusRawatId,
            "alatOksigenId" => $post->alatOksigenId,
            "penyintasId" => $post->penyintasId,
            "tanggalOnsetGejala" => $post->tanggalOnsetGejala,
            "kelompokGejalaId" => $post->kelompokGejalaId,
            "gejala" => [
                "demamId" => $post->demamId,
                "batukId" => $post->batukId,
                "pilekId" => $post->pilekId,
                "sakitTenggorokanId" => $post->sakitTenggorokanId,
                "sesakNapasId" => $post->sesakNapasId,
                "lemasId" => $post->lemasId,
                "nyeriOtotId" => $post->nyeriOtotId,
                "mualMuntahId" => $post->mualMuntahId,
                "diareId" => $post->diareId,
                "anosmiaId" => $post->anosmiaId,
                "napasCepatId" => $post->napasCepatId,
                "frekNapas30KaliPerMenitId" => $post->frekNapas30KaliPerMenitId,
                "distresPernapasanBeratId" => $post->distresPernapasanBeratId,
                "lainnyaId" => $post->lainnyaId,
            ]
        ];
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->patch($this->base_url().'laporancovid19versi3/'.$post->id_laporan,$body);

        $data = json_decode($response->body());

        if($data->status) return $data;
        if($data->status == false) return $data;

        $this->handleExpired();
        return $this->updateData($post);
    }

    public function kewarganegaraan()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'kewarganegaraan');

        $data = json_decode($response->body());

        if($data->status) return $data->data;
        if($data->status == false) return $data;
        // return $this->kewarganegaraan();
    }

    public function asalpasien()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'asalpasien');

        $data = json_decode($response->body());

        if($data->status) return $data->data;
        if($data->status == false) return $data;
        // return $this->asalpasien();
    }

    public function kecamatan()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'kecamatan');

        $data = json_decode($response->body());

        if($data->status == false) return $data;
        $total_number = $data->pagination->total_number_of_pages;

        $arr_data = [];
        for($i = 1; $i <= $total_number; $i++) {
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Authorization' => 'Bearer '.$this->getToken()
            ])->get($this->base_url().'kecamatan?page='.$i.'&limit=1000');

            $_data = json_decode($response->body());
            if($_data->status) {
                $arr = $_data->data;
                array_push($arr_data, $arr);
            }
        }
        if($data->status) return $arr_data;
        if($data->status == false) return $data;
        // return $this->kecamatan();
    }

    public function kabkota()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'kabkota?page=1&limit=1000');

        $data = json_decode($response->body());

        if($data->status) return $data->data;
        if($data->status == false) return $data;
        // return $this->kabkota();
    }

    public function provinsi()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'provinsi');

        $data = json_decode($response->body());

        if($data->status) return $data->data;
        if($data->status == false) return $data;
        // return $this->provinsi();
    }

    public function pekerjaan()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'pekerjaan');

        $data = json_decode($response->body());

        if($data->status) {
            return $data->data;
        }
        if($data->status == false) return $data;
        // return $this->pekerjaan();
    }

    public function jenispasien()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'jenispasien');

        $data = json_decode($response->body());

        if($data->status) {
            return $data->data;
        }
        if($data->status == false) return $data;
        // return $this->jenispasien();
    }

    public function statuspasien()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'statuspasien');

        $data = json_decode($response->body());

        if($data->status) {
            return $data->data;
        }
        if($data->status == false) return $data;
        // return $this->statuspasien();
    }

    public function statusrawat()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'statusrawat');

        $data = json_decode($response->body());

        if($data->status) {
            return $data->data;
        }
        if($data->status == false) return $data;
        // return $this->statusrawat();
    }

    public function alatoksigen()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'alatoksigen');

        $data = json_decode($response->body());

        if($data->status) {
            return $data->data;
        }
        if($data->status == false) return $data;
        // return $this->alatoksigen();
    }

    public function kelompokgejala()
    {
        $this->handleExpired();
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Authorization' => 'Bearer '.$this->getToken()
        ])->get($this->base_url().'kelompokgejala');

        $data = json_decode($response->body());

        if($data->status) {
            return $data->data;
        }
        if($data->status == false) return $data;
        // return $this->kelompokgejala();
    }

    public function datakomorbidcovid()
    {
        $response = Http::get($this->url_sirs().'data_komorbidcovid');
        $data = json_decode($response->body());

        return $data;
    }

    public function dataobatcovid()
    {
        $response = Http::get($this->url_sirs().'data_obatcovid');
        $data = json_decode($response->body());

        return $data;
    }
}
