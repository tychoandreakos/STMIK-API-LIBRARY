<?php

namespace App\Http\Controllers;

use App\Gmd;
use App\Exceptions\ResponseException;
use Illuminate\Http\Request;

class GmdController extends Controller
{
  /**
   * Fungsi ini berfungsi untuk mendapakatkan data dari database. Response yang diterima
   * adalah seluruh data GMD.
   *
   * @return JSON $json;
   */
  public function index(Request $request)
  {
    try {
      /**
       * Berfungsi untuk pagination.
       * Misalkan $request->skip === 1,
       * maka akan dikali 2 menadi 1 * 2 = 2;
       * 2 data akan diskip.
       *
       * untuk $request->take, artinya adalah untuk mengambil hanya 5 data saja.
       */
      $skip = $request->input('skip') ? $request->input('skip') * 2 : 0;
      $take = $request->take ? $request->take : 5;

      $data = Gmd::all()
        ->skip($skip)
        ->take($take);
      $message = 200;
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => "Sukses",
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
   * @return JSON json
   */
  public function store(Gmd $gmd, Request $request)
  {
    try {
      $gmd->gmd_code = strtolower($request->gmd_code);
      $gmd->gmd_name = strtolower($request->gmd_name);
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
   * Fungsi ini bertugas untuk mencari data sesuai dengan parameters yang sudah diinginkan,
   *
   * @param Request $request;
   * @return JSON $json
   */
  public function search(Request $request)
  {
    try {
      $data = Gmd::where('gmd_code', $request->search)
        ->orWhere('gmd_name', 'LIKE', "%$request->search%")
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
      } elseif (!$data && count($data) < 0) { // error jika data tidak ada
        $msg = "Data tidak Dapat ditemukan";
        $code = 404;
        throw new ResponseException($msg, $code);
      } else { // error terjadi ketika tidak ada error atapun ada kesalahan yang tidak dinginkan
        $msg = "Telah Terjadi Error Pada Server";
        $code = 500;
        throw new ResponseException($msg, $code);
      }
    } catch (ResponseException $th) {
      $message = $th->getCode();
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
   *  Fungsi atau method ini berguna untuk menampilkan detail item GMD.
   *
   * @param Request $request
   * @return JSON;
   */
  public function detail(Request $request)
  {
    try {
      $data = GMD::find($request->id);
      if ($data) {
        $message = 200;
        $response = [
          'time' => time(),
          'status' => $message,
          'data' => $data,
          'message' => 'Sukses'
        ];

        return response($response, $message);
      } else {
        $msg = "Data tidak ditemukan";
        $code = 404;
        throw new ResponseException($msg, $code);
      }
    } catch (ResponseException $th) {
      $message = $th->getCode();
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => 'Gagal',
        'exception' => $th->getMessage()
      ];

      return response($response, $message);
    }
  }

  /**
   *
   * Fungsi ini bertugas untuk mengupdate data yang ada didalam database GMD.
   * Data yang diubah sesuai dengan $id dalam parameter yang diberikan
   *
   * @param String $id,
   * @param Request $request
   * @return JSON $reponse;
   */
  public function update(string $id, Request $request)
  {
    try {
      $gmd = Gmd::find($id);

      $gmd->gmd_code = strtolower($request->input('gmd_code'));
      $gmd->gmd_name = strtolower($request->input('gmd_name'));
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
   * @param String $id
   * @return JSON $json
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
        'message' => 'Gagal Dihapus',
        'exception' => $th->getMessage()
      ];

      return response($response, $message);
    }
  }

  public function destroyAll()
  {
    try {
      Gmd::truncate();
      $message = 200;
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => 'Berhasil Menghapus Semua Data'
      ];

      return response($response, $message);
    } catch (\Throwable $th) {
      $message = 500;
      $response = [
        'time' => time(),
        'status' => $message,
        'message' => 'Gagal Menghapus Semua Data',
        'exception' => $th->getMessage()
      ];

      return response($response, $message);
    }
  }
}
