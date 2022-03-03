<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ApiSiranapController extends Controller
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
    
    public function getReferensiTT()
    {
        $response = Http::withHeaders($this->header())->get($this->base_url().'Referensi/tempat_tidur');

        return json_decode($response->body())->tempat_tidur;
    }

    public function getTT()
    {
        $response = Http::withHeaders($this->header())->get($this->base_url().'Fasyankes');

        return json_decode($response->body())->fasyankes;
    }

    public function hapusTT($post)
    {
        if($post->id_t_tt == null) {
            $ruangan = Ruangan::where('id', $post->id)->delete();
            if($ruangan) {
                return 'Berhasil Dihapus!';
            }
        } else {
            $body = [
                'id_t_tt' => $post->id_t_tt,
            ];
            $response = Http::withHeaders(
                $this->header()
            )->delete($this->base_url().'Fasyankes',$body);
            $IsDelete = preg_match('/telah dihapus/i', json_decode($response->body())->fasyankes[0]->message);
            if($IsDelete) {
                Ruangan::where('id', $post->id)->delete();
                return 'Berhasil Dihapus!';
            } else {
                return json_decode($response->body())->fasyankes[0]->message;
            }
        }
    }

    public function simpanTT($post)
    {
        if($post->id_t_tt == null) {
            $body = [
                "id_tt" => $post->id_tt,
                "ruang" => $post->ruang, 
                "jumlah_ruang" => $post->jumlah_ruang, 
                "jumlah" => $post->jumlah, 
                "terpakai" => $post->terpakai, 
                "terpakai_suspek" => $post->covid == 0 ? 0: $post->terpakai_suspek, 
                "terpakai_konfirmasi" => $post->covid == 0 ? 0: $post->terpakai_konfirmasi, 
                "antrian" => $post->antrian, 
                "prepare" => $post->prepare, 
                "prepare_plan" => $post->prepare_plan, 
                "covid" => $post->covid
            ];
            $response = Http::withHeaders(
                $this->header()
            )->post($this->base_url().'Fasyankes',$body);
            
            $IsSave = preg_match('/telah disimpan/i', json_decode($response->body())->fasyankes[0]->message);
            if($IsSave) {
                return 'Berhasil Simpan!';
            } else {
                return json_decode($response->body())->fasyankes[0]->message;
            }
        }
        if($post->id_t_tt != null)
        {
            $body = [
                "id_t_tt" => $post->id_t_tt,
                "ruang" => $post->ruang, 
                "jumlah_ruang" => $post->jumlah_ruang, 
                "jumlah" => $post->jumlah, 
                "terpakai" => $post->terpakai, 
                "terpakai_suspek" => $post->covid == 0 ? 0: $post->terpakai_suspek,
                "terpakai_konfirmasi" => $post->covid == 0 ? 0: $post->terpakai_konfirmasi, 
                "antrian" => $post->antrian, 
                "prepare" => $post->prepare, 
                "prepare_plan" => $post->prepare_plan, 
                "covid" => $post->covid
            ];
            
            $response = Http::withHeaders(
                $this->header()
            )->put($this->base_url().'Fasyankes',$body);
            $IsUpdate = preg_match('/telah diupdate/i', json_decode($response->body())->fasyankes[0]->message);
            if($IsUpdate) {
                Ruangan::where('id', $post->id)->update([
                    'butuh_sinkron_ulang' => 0
                ]);
                return 'Berhasil Update!';
            } else {
                return json_decode($response->body())->fasyankes[0]->message;
            }
        }
    }
}
