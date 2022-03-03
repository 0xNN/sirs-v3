<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralCodeController extends Controller
{
    public static function parseSex($gcsex)
    {
        $model = [
            '0001^A' => 'Ambigu',
            '0001^F' => 'Perempuan',
            '0001^M' => 'Laki-Laki',
            '0001^N' => 'Tidak diterapkan',
            '0001^U' => 'Tidak diketahui',
            '0001^O' => 'Lainnya',
        ];
        return isset($model[$gcsex]) ? $model[$gcsex] : 'Tidak ditemukan!';
    }

    public static function parseJK($jk)
    {
        $model = [
            'Laki-Laki' => 'L',
            'Laki-laki' => 'L',
            'Perempuan' => 'P',
            'Wanita' => 'P'
        ];
        return isset($model[$jk]) ? $model[$jk] : 'Tidak ditemukan!';
    }

    public static function parseEducation($gcedu)
    {
        $model = [
            'X0013^01' => 'Tamat TK',
            'X0013^02' => 'Tamat SD',
            'X0013^03' => 'Tamat SMP',
            'X0013^04' => 'Tamat SMA',
            'X0013^05' => 'Tamat D-1',
            'X0013^06' => 'Tamat D-3',
            'X0013^07' => 'Tamat S-1',
            'X0013^08' => 'Tamat S-2',
            'X0013^09' => 'Tamat S-3'
        ];
        return isset($model[$gcedu]) ? $model[$gcedu] : 'Tidak ditemukan!';
    }

    public static function parseNationality($gcnational)
    {
        // List Negara https://datahub.io/core/country-list (ISO 3166-1)

        // Tambahkan disini dari sphaira
        $model = [
            'X0014^001' => 'Indonesia', // Penamaan disesuaikan dengan list negara 
            'X0014^002' => 'China',
            'X0014^004' => 'France'
        ];
        return isset($model[$gcnational]) ? $model[$gcnational] : 'Tidak ditemukan!';
    }

    public static function parseCountries($country)
    {
        // Tambahkan disini dari list negara
        $model = [
            'Indonesia' => 'ID',
            'China' => 'CN',
            'France' => 'FR',
        ];
        return isset($model[$country]) ? $model[$country] : 'Tidak ditemukan!';
    }

    public static function parseMarital($gcmarital)
    {
        // Tambahkan disini dari sphaira
        $model = [
            '0002^A' => 'Terpisah',
            '0002^B' => 'Belum Menikah',
            '0002^C' => 'Hukum adat',
            '0002^D' => 'Cerai',
            '0002^E' => 'Resmi Bercerai',
            '0002^G' => 'Hidup bersama',
            '0002^M' => 'Menikah',
            '0002^S' => 'Sendiri',
            '0002^U' => 'Tidak diketahui'
        ];
        return isset($model[$gcmarital]) ? $model[$gcmarital] : 'Tidak ditemukan!';
    }

    public static function parseReligion($gcreligion)
    {
        $model = [
            '0006^BUD' => 'Budha',
            '0006^CHR' => 'Kristen',
            '0006^CNF' => 'Kong Fu Cu',
            '0006^CTH' => 'Katolik',
            '0006^HIN' => 'Hindu',
            '0006^MOS' => 'Islam',
            '0006^OTH' => 'Lainnya',
        ];
        return isset($model[$gcreligion]) ? $model[$gcreligion] : 'Tidak ditemukan!';
    }

    public static function parseBlood($gcblood)
    {
        $model = [
            'X0009^A' => 'A',
            'X0009^B' => 'B',
            'X0009^AB' => 'AB',
            'X0009^O' => 'O',
            'X0009^N/A' => 'N/A',
        ];
        return isset($model[$gcblood]) ? $model[$gcblood] : 'Tidak ditemukan!';
    }

    public static function parseOccupation($gcoccu)
    {
        $model = [
            'X0012^01' => 'Pegawai Swasta',
            'X0012^02' => 'TNI/Polri/PNS/BUMN',
            'X0012^03' => 'Buruh dan Lainnya',
            'X0012^04' => 'Tidak kerja/sekolah/IRT',
            'X0012^05' => 'Wiraswasta/Dagang/Jasa',
            'X0012^06' => 'Wiraswasta/Dagang/Jasa',
            'X0012^07' => 'Petani/Nelayan',
            'X0012^08' => 'Petani/Nelayan',
        ];
        return isset($model[$gcoccu]) ? $model[$gcoccu] : 'Tidak ditemukan!';
    }
    
    public static function parsePatientCat($gcpatientcat)
    {
        $model = [
            'X0010^01' => 'Karyawan',
            'X0010^02' => 'Bergantung',
            'X0010^03' => 'Mitra bisnis',
            'X0010^04' => 'Sipil',
            'X0010^05' => 'Kerabat Karyawan'
        ];
        return isset($model[$gcpatientcat]) ? $model[$gcpatientcat] : 'Tidak ditemukan!';
    }

    public static function parsePatientType($gcpatienttype)
    {
        $model = [
            'X0024^E' => 'Emergency',
            'X0024^I' => 'Inpatient',
            'X0024^M' => 'Medical Check Up',
            'X0024^O' => 'Outpatient',
            'X0024^W' => 'Walk in'
        ];
        return isset($model[$gcpatienttype]) ? $model[$gcpatienttype] : 'Tidak ditemukan!';
    }

    public static function parseDischargeMethod($gcdm)
    {
        $model = [
            'X0033^001' => 'Atas Persetujuan Dokter',
            'X0033^002' => 'Being Referred',
            'X0033^003' => 'Pindah ke RS Lain',
            'X0033^004' => 'Atas Permintaan Sendiri',
            'X0033^005' => 'Dirujuk',
            'X0033^006' => 'Lain-lain',
            'X0033^007' => 'Meninggal'
        ];
        return isset($model[$gcdm]) ? $model[$gcdm] : 'Tidak ditemukan!';
    }

    public static function parseDischargeCondition($gcco)
    {
        $model = [
            'X0034^001' => 'Cured',
            'X0034^002' => 'Better',
            'X0034^003' => 'Not Cured',
            'X0034^004' => 'Dead in Less Than 48 Hours',
            'X0034^005' => 'Dead in More Than or Equal to 48 Hours',
            'X0034^006' => 'Recovery',
            'X0034^007' => 'Improvement',
            'X0034^008' => 'Healthy'
        ];
        return isset($model[$gcco]) ? $model[$gcco] : 'Tidak ditemukan!';
    }

    public function parseBedStatus($gcbs)
    {
        $model = [
            '0116^B' => 'Block',
            '0116^C' => 'Pembersihan',
            '0116^O' => 'Ditempati',
            '0116^P' => 'Rencana Pelepasan',
            '0116^R' => 'Tersedia',
            '0116^W' => 'Akan Transfer',
            '0116^X' => 'Tidak Digunakan'
        ];
        return isset($model[$gcbs]) ? $model[$gcbs] : 'Tidak ditemukan!';
    }

    public function parseParamedicType($gcpt)
    {
        $model = [
            'X0055^001' => 'Dokter',
            'X0055^002' => 'Bidan',
            'X0055^003' => 'Perawat',
            'X0055^004' => 'Perawat Gigi',
            'X0055^005' => 'Terapis Fisik',
            'X0055^006' => 'Ahli Anestesi',
            'X0055^007' => 'Analis Laboratorium',
            'X0055^008' => 'Radiografer',
            'X0055^009' => 'Apoteker',
            'X0055^010' => 'Asisten Apoteker',
            'X0055^011' => 'Petugas Rekam Medis',
            'X0055^012' => 'Fisioterapis',
            'X0055^013' => 'Ahli Ilmu Gizi',
        ];
        return isset($model[$gcpt]) ? $model[$gcpt] : 'Tidak ditemukan!';
    }

    public function parseEmploymentStatus($gces)
    {
        $model = [
            'X0020^001' => 'Kontrak',
            'X0020^002' => 'Permanen'
        ];
        return isset($model[$gces]) ? $model[$gces] : 'Tidak ditemukan!';
    }

    private static function parse($var)
    {
        return isset($var) ? $var : 'Tidak ditemukan!';
    }
}

