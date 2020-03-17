<?php

namespace App\Http\Controllers;

use App\Gmd;
use Illuminate\Http\Request;

class GmdController extends Controller
{
  public function store(Gmd $gmd, Request $request)
  {
    try {
      $gmd->gmd_code = $request->gmd_code;
      $gmd->gmd_name = $request->gmd_name;
      $gmd->save();

      $message = 200;
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => 'Data Berhasil Disimpan'
      ];

      return response($response, $message);
    } catch (\Throwable $th) {
      $message = 500;
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => 'Data Gagal Disimpan',
        'exception' => $th->getMessage()
      ];

      return response($response, $message);
    }
  }
}
