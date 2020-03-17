<?php

namespace App\Http\Controllers;

use App\Gmd;
use Illuminate\Http\Request;

class GmdController extends Controller
{
  /**
   * Fungsi ini berfungsi untuk mendapakatkan data dari database. Response yang diterima
   * adalah seluruh data GMD.
   *
   * @return response $json;
   */
  public function index()
  {
    try {
      $data = Gmd::all();
      $message = 200;
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => "Data Berhasil Diambil",
        "data" => $data
      ];

      return response($response, $message);
    } catch (\Throwable $th) {
      $message = 500;
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => "Data Gagal Diambil",
        "exception" => $th->getMessage()
      ];

      return response($response, $message);
    }
  }

  /**
   * Ini fungsi untuk menyimpan data GMD kedalam database menggunakan
   * class Request & Gmd sebagai Param
   * @param $gmd
   * @param $request
   * @return response json
   */
  public function store(Gmd $gmd, Request $request)
  {
    try {
      $gmd->gmd_code = $request->gmd_code;
      $gmd->gmd_name = $request->gmd_name;
      $gmd->save();

      $message = 201;
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

  /**
   *
   * Fungsi ini bertugas untuk mengupdate data yang ada didalam database GMD.
   * Data yang diubah sesuai dengan $id dalam argument yang diberikan
   *
   * @param String $id,
   * @param Request $request
   * @return response $reponse;
   */
  public function update(string $id, Request $request)
  {
    try {
      $gmd = Gmd::find($id);

      $message = 200;
      $response = [
        'time' => time(),
        'status' => $message,
        'data' => $gmd,
        'message' => 'Berhasil Diubah'
      ];

      return response($response, $message);
    } catch (\Throwable $th) {
      $message = 500;
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => 'Gagal Diubah',
        'exception' => $th->getMessage()
      ];

      return response($response, $message);
    }
  }
}
