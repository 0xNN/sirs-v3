<?php

namespace App\Http\Controllers;

use App\Models\NakesTerinfeksi;
use App\Models\Oksigenasi;
use App\Models\PcrNakes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiPcrNakesController extends Controller
{
    public function base_url()
    {
        return config('myconfig.api.base_url2');
    }

    public function header($tgl = null)
    {
        return [
            'X-rs-id' => config('myconfig.login2.X-rs-id'),
            'X-Timestamp' => Carbon::now()->timestamp,
            'X-pass' => config('myconfig.login2.X-pass'),
            'X-tanggal' => $tgl
        ];
    }

    public function getPcrNakes($tgl = null)
    {
        $response = Http::withHeaders($this->header($tgl))->get($this->base_url().'Pasien/pcr_nakes');
        $dec = json_decode($response->body())->PCRNakes;
        if(isset($dec[0]->status)) {
            if($dec[0]->status == '202') {
                return $dec[0]->status;
            }
        }
        return json_decode($response->body())->PCRNakes;
    }

    public function getOksigenasi($tgl = null)
    {
        $response = Http::withHeaders($this->header($tgl))->get($this->base_url().'Logistik/oksigen');
        $dec = json_decode($response->body())->Oksigenasi;
        if(isset($dec[0]->status)) {
            if($dec[0]->status == '202') {
                return $dec[0]->status;
            }
        }
        return json_decode($response->body())->Oksigenasi;
    }

    public function getNakesTerinfeksi($tgl = null)
    {
        $response = Http::withHeaders($this->header($tgl))->get($this->base_url().'Pasien/harian_nakes_terinfeksi');
        $dec = json_decode($response->body())->HarianNakesTerinfeksi;
        if(isset($dec[0]->status)) {
            if($dec[0]->status == '202') {
                return $dec[0]->status;
            }
        }
        return json_decode($response->body())->HarianNakesTerinfeksi;
    }

    public function simpanPcrNakes($post)
    {
        $body = [
            'tanggal' => $post->tanggal,
            'jumlah_tenaga_dokter_umum' => $post->jumlah_tenaga_dokter_umum,
            'sudah_periksa_dokter_umum' => $post->sudah_periksa_dokter_umum,
            'hasil_pcr_dokter_umum' => $post->hasil_pcr_dokter_umum,
            'jumlah_tenaga_dokter_spesialis' => $post->jumlah_tenaga_dokter_spesialis,
            'sudah_periksa_dokter_spesialis' => $post->sudah_periksa_dokter_spesialis,
            'hasil_pcr_dokter_spesialis' => $post->hasil_pcr_dokter_spesialis,
            'jumlah_tenaga_dokter_gigi' => $post->jumlah_tenaga_dokter_gigi,
            'sudah_periksa_dokter_gigi' => $post->sudah_periksa_dokter_gigi,
            'hasil_pcr_dokter_gigi' => $post->hasil_pcr_dokter_gigi,
            'jumlah_tenaga_residen' => $post->jumlah_tenaga_residen,
            'sudah_periksa_residen' => $post->sudah_periksa_residen,
            'hasil_pcr_residen' => $post->hasil_pcr_residen,
            'jumlah_tenaga_perawat' => $post->jumlah_tenaga_perawat,
            'sudah_periksa_perawat' => $post->sudah_periksa_perawat,
            'hasil_pcr_perawat' => $post->hasil_pcr_perawat,
            'jumlah_tenaga_bidan' => $post->jumlah_tenaga_bidan,
            'sudah_periksa_bidan' => $post->sudah_periksa_bidan,
            'hasil_pcr_bidan' => $post->hasil_pcr_bidan,
            'jumlah_tenaga_apoteker' => $post->jumlah_tenaga_apoteker,
            'sudah_periksa_apoteker' => $post->sudah_periksa_apoteker,
            'hasil_pcr_apoteker' => $post->hasil_pcr_apoteker,
            'jumlah_tenaga_radiografer' => $post->jumlah_tenaga_radiografer,
            'sudah_periksa_radiografer' => $post->sudah_periksa_radiografer,
            'hasil_pcr_radiografer' => $post->hasil_pcr_radiografer,
            'jumlah_tenaga_analis_lab' => $post->jumlah_tenaga_analis_lab,
            'sudah_periksa_analis_lab' => $post->sudah_periksa_analis_lab,
            'hasil_pcr_analis_lab' => $post->hasil_pcr_analis_lab,
            'jumlah_tenaga_co_ass' => $post->jumlah_tenaga_co_ass,
            'sudah_periksa_co_ass' => $post->sudah_periksa_co_ass,
            'hasil_pcr_co_ass' => $post->hasil_pcr_co_ass,
            'jumlah_tenaga_internship' => $post->jumlah_tenaga_internship,
            'sudah_periksa_internship' => $post->sudah_periksa_internship,
            'hasil_pcr_internship' => $post->hasil_pcr_internship,
            'jumlah_tenaga_nakes_lainnya' => $post->jumlah_tenaga_nakes_lainnya,
            'sudah_periksa_nakes_lainnya' => $post->sudah_periksa_nakes_lainnya,
            'hasil_pcr_nakes_lainnya' => $post->hasil_pcr_nakes_lainnya,
            'rekap_jumlah_tenaga' => $post->rekap_jumlah_tenaga,
            'rekap_jumlah_sudah_diperiksa' => $post->rekap_jumlah_sudah_diperiksa,
            'rekap_jumlah_hasil_pcr' => $post->rekap_jumlah_hasil_pcr,
            'tgllapor' => $post->tgllapor,
        ];
        try {
            $response = Http::withHeaders(
                $this->header()
            )->post($this->base_url().'Pasien/pcr_nakes',$body);
        } catch (Exception $e) {
            return 'Kesalahan Jaringan! Coba lagi!';
        }
        $IsSave = preg_match('/telah disimpan/i', json_decode($response->body())->PCRNakes[0]->message);
        if($IsSave) {
            PcrNakes::where('id', $post->id)->update([
                'butuh_sinkron_ulang' => 0,
                'status_sinkron' => 1,
            ]);
            return 'Berhasil Kirim!';
        } else {
            $IsUpdate = preg_match('/telah diupdate/i', json_decode($response->body())->PCRNakes[0]->message);
            if($IsUpdate) {
                return 'Berhasil Update (Kemkes)!';
            }
            return json_decode($response->body())->PCRNakes[0]->message;
        }
    }

    public function simpanOksigenasi($post)
    {
        $body = [
            'tanggal' => $post->tanggal,
            'p_cair' => $post->p_cair,
            'p_tabung_kecil' => $post->p_tabung_kecil,
            'p_tabung_sedang' => $post->p_tabung_sedang,
            'p_tabung_besar' => $post->p_tabung_besar,
            'k_isi_cair' => $post->k_isi_cair,
            'k_isi_tabung_kecil' => $post->k_isi_tabung_kecil,
            'k_isi_tabung_sedang' => $post->k_isi_tabung_sedang,
            'k_isi_tabung_besar' => $post->k_isi_tabung_besar,
        ];
        try {
            $response = Http::withHeaders(
                $this->header()
            )->post($this->base_url().'Logistik/oksigen',$body);
        } catch (Exception $e) {
            return 'Kesalahan Jaringan! Coba lagi!';
        }
        $IsSave = preg_match('/telah disimpan/i', json_decode($response->body())->Oksigenasi[0]->message);
        if($IsSave) {
            Oksigenasi::where('id', $post->id)->update([
                'butuh_sinkron_ulang' => 0,
                'status_sinkron' => 1,
            ]);
            return 'Berhasil Kirim!';
        } else {
            $IsUpdate = preg_match('/telah diupdate/i', json_decode($response->body())->Oksigenasi[0]->message);
            if($IsUpdate) {
                return 'Berhasil Update (Kemkes)!';
            }
            return json_decode($response->body())->Oksigenasi[0]->message;
        }
    }

    public function simpanNakesTerinfeksi($post)
    {
        $body = [
            'tanggal' => $post->tanggal,
            'co_ass' => $post->co_ass,
            'residen' => $post->residen,
            'intership' => $post->intership,
            'dokter_spesialis' => $post->dokter_spesialis,
            'dokter_umum' => $post->dokter_umum,
            'dokter_gigi' => $post->dokter_gigi,
            'perawat' => $post->perawat,
            'bidan' => $post->bidan,
            'apoteker' => $post->apoteker,
            'radiografer' => $post->radiografer,
            'analis_lab' => $post->analis_lab,
            'nakes_lainnya' => $post->nakes_lainnya,
            'co_ass_meninggal' => $post->co_ass_meninggal,
            'residen_meninggal' => $post->residen_meninggal,
            'intership_meninggal' => $post->intership_meninggal,
            'dokter_spesialis_meninggal' => $post->dokter_spesialis_meninggal,
            'dokter_umum_meninggal' => $post->dokter_umum_meninggal,
            'dokter_gigi_meninggal' => $post->dokter_gigi_meninggal,
            'perawat_meninggal' => $post->perawat_meninggal,
            'bidan_meninggal' => $post->bidan_meninggal,
            'apoteker_meninggal' => $post->apoteker_meninggal,
            'radiografer_meninggal' => $post->radiografer_meninggal,
            'analis_lab_meninggal' => $post->analis_lab_meninggal,
            'nakes_lainnya_meninggal' => $post->nakes_lainnya_meninggal,
            'co_ass_dirawat' => $post->co_ass_dirawat,
            'co_ass_isoman' => $post->co_ass_isoman,
            'co_ass_sembuh' => $post->co_ass_sembuh,
            'residen_dirawat' => $post->residen_dirawat,
            'residen_isoman' => $post->residen_isoman,
            'residen_sembuh' => $post->residen_sembuh,
            'intership_dirawat' => $post->intership_dirawat,
            'intership_isoman' => $post->intership_isoman,
            'intership_sembuh' => $post->intership_sembuh,
            'dokter_spesialis_dirawat' => $post->dokter_spesialis_dirawat,
            'dokter_spesialis_isoman' => $post->dokter_spesialis_isoman,
            'dokter_spesialis_sembuh' => $post->dokter_spesialis_sembuh,
            'dokter_umum_dirawat' => $post->dokter_umum_dirawat,
            'dokter_umum_isoman' => $post->dokter_umum_isoman,
            'dokter_umum_sembuh' => $post->dokter_umum_sembuh,
            'dokter_gigi_dirawat' => $post->dokter_gigi_dirawat,
            'dokter_gigi_isoman' => $post->dokter_gigi_isoman,
            'dokter_gigi_sembuh' => $post->dokter_gigi_sembuh,
            'bidan_dirawat' => $post->bidan_dirawat,
            'bidan_isoman' => $post->bidan_isoman,
            'bidan_sembuh' => $post->bidan_sembuh,
            'apoteker_dirawat' => $post->apoteker_dirawat,
            'apoteker_isoman' => $post->apoteker_isoman,
            'apoteker_sembuh' => $post->apoteker_sembuh,
            'radiografer_dirawat' => $post->radiografer_dirawat,
            'radiografer_isoman' => $post->radiografer_isoman,
            'radiografer_sembuh' => $post->radiografer_sembuh,
            'analis_lab_dirawat' => $post->analis_lab_dirawat,
            'analis_lab_isoman' => $post->analis_lab_isoman,
            'analis_lab_sembuh' => $post->analis_lab_sembuh,
            'nakes_lainnya_dirawat' => $post->nakes_lainnya_dirawat,
            'nakes_lainnya_isoman' => $post->nakes_lainnya_isoman,
            'nakes_lainnya_sembuh' => $post->nakes_lainnya_sembuh,
            'perawat_dirawat' => $post->perawat_dirawat,
            'perawat_isoman' => $post->perawat_isoman,
            'perawat_sembuh' => $post->perawat_sembuh,
        ];
        try {
            $response = Http::withHeaders(
                $this->header()
            )->post($this->base_url().'Pasien/harian_nakes_terinfeksi',$body);
        } catch (Exception $e) {
            return 'Kesalahan Jaringan! Coba lagi!';
        }
        $IsSave = preg_match('/telah disimpan/i', json_decode($response->body())->HarianNakesTerinfeksi[0]->message);
        if($IsSave) {
            NakesTerinfeksi::where('id', $post->id)->update([
                'butuh_sinkron_ulang' => 0,
                'status_sinkron' => 1,
            ]);
            return 'Berhasil Kirim!';
        } else {
            $IsUpdate = preg_match('/telah diupdate/i', json_decode($response->body())->HarianNakesTerinfeksi[0]->message);
            if($IsUpdate) {
                return 'Berhasil Update (Kemkes)!';
            }
            return json_decode($response->body())->HarianNakesTerinfeksi[0]->message;
        }
    }
}
