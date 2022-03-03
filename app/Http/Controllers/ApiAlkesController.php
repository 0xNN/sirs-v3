<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiAlkesController extends Controller
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

    public function getReferensiAlkes()
    {
        $response = Http::withHeaders($this->header())->get($this->base_url().'Referensi/kebutuhan_apd');

        return json_decode($response->body())->kebutuhan_apd;
    }

    public function getAlkes()
    {
        $response = Http::withHeaders($this->header())->get($this->base_url().'Fasyankes/apd');

        return json_decode($response->body())->apd;
    }
}
