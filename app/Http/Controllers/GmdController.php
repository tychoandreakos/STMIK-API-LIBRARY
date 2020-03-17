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
   * Fungsi ini bertugas untuk mencari data sesuai dengan arguments yang sudah diinginkan,
   * 
   * @param Request $request;
   * @return response $json
   */
  public function search(Request $request)
  {
    try {
      $data = Gmd::where('gmd_code', $request->search)
        ->orWhere('gmd_name', 'LIKE' ,"%$request->search%")
        ->get();
      if ($data && count($data) > 0) {
        $message = 200;
        $response = [
          'time' => time(),
          'status' => $message,
          'data' => [
            'querySearch' => $request->search,
            'result' => $data
          ],
          'message' => 'Sukses'
        ];

        return response($response, $message);
      } else {
        throw new \Exception("Data Tidak Dapat Ditemukan", 1);
      }
    } catch (\Throwable $th) {
      $message = 404;
      $response = [
        'time' => time(),
        'status' => $message,
        'querySearch' => $request->search,
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

      $gmd->gmd_code = $request->gmd_code;
      $gmd->gmd_name = $request->gmd_name;
      $gmd->save();

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

  /**
   * Fungsi ini bertugas untuk menghapus data yang ada didalam database menggunakan methode Hard Delete.
   *
   * @return String $id
   */
  public function destroy(string $id)
  {
    try {
      $gmd = Gmd::find($id);
      $gmd->delete();

      $message = 200;
      $response = [
        'time' => time(),
        'status' => $message,
        'data' => [
          'id' => $id
        ],
        'message' => 'Berhasil Dihapus'
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
