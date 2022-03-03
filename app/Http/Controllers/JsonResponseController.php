<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonResponseController extends Controller
{
    public static function jsonData($data = null, $message = '')
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public static function jsonDataWithIcon($data = null, $message = '', $icon = 'success')
    {
        return response()->json([
            'code' => 200,
            'icon' => $icon,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public static function jsonDataGUS($data = null, $message = '', $icon = 'success', $gus = [])
    {
        return response()->json([
            'code' => 200,
            'icon' => $icon,
            'message' => $message,
            'data' => $data,
            'gagal' => $gus['g'],
            'update' => $gus['u'],
            'simpan' => $gus['s'],
        ]);
    }
}
