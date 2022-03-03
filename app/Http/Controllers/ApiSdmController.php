<?php

namespace App\Http\Controllers;

use App\Models\SDM;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiSdmController extends Controller
{
    public function base_url()
    {
        return config('myconfig.api.base_url2');
    }

    public function header()
    {
        return [
            'X-rs-id' => config('myconfig.login2.X-rs-id'),
            'X-Timestamp' => Carbon::now()->timestamp,
            'X-pass' => config('myconfig.login2.X-pass')
        ];
    }

    public function getReferensiSdm()
    {
        $response = Http::withHeaders($this->header())->get($this->base_url().'Referensi/kebutuhan_sdm');

        return json_decode($response->body())->kebutuhan_sdm;
    }

    public function getSdm()
    {
        $response = Http::withHeaders($this->header())->get($this->base_url().'Fasyankes/sdm');

        return json_decode($response->body())->sdm;
    }

    public function simpanSdm($post)
    {
        $body = [
            'id_kebutuhan' => $post->id_kebutuhan,
            'jumlah_eksisting' => $post->jumlah_eksisting,
            'jumlah' => $post->jumlah,
            'jumlah_diterima' => $post->jumlah_diterima,
        ];
        if($post->kebutuhan != null) {
            try {
                $response = Http::withHeaders(
                    $this->header()
                )->put($this->base_url().'Fasyankes/sdm',$body);
            } catch (Exception $e) {
                return 'Kesalahan Jaringan! Coba lagi!';
            }
            $IsUpdate = preg_match('/telah diupdate/i', json_decode($response->body())->sdm[0]->message);
            if($IsUpdate) {
                SDM::where('id', $post->id)->update([
                    'butuh_sinkron_ulang' => 0
                ]);
                return 'Berhasil Update!';
            } else {
                return json_decode($response->body())->sdm[0]->message;
            }
        } else {
            try {
                $response = Http::withHeaders(
                    $this->header()
                )->post($this->base_url().'Fasyankes/sdm',$body);
            } catch (Exception $e) {
                return 'Kesalahan Jaringan! Coba lagi!';
            }
            $IsSave = preg_match('/telah disimpan/i', json_decode($response->body())->sdm[0]->message);
            if($IsSave) {
                SDM::where('id', $post->id)->update([
                    'butuh_sinkron_ulang' => 0,
                ]);
                return 'Berhasil Simpan!';
            } else {
                return json_decode($response->body())->sdm[0]->message;
            }
        }
    }
}
