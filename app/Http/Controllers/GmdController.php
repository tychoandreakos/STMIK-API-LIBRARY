<?php

namespace App\Http\Controllers;

use App\Gmd;
use App\Exceptions\ResponseException;
use Illuminate\Http\Request;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;

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
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $data = Gmd::all()
        ->skip($skip)
        ->take($take);

      $response = 200;

      $sendData = [$response, 'Sukses', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = 500;

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
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

      $response = 201;

      $sendData = [$response, 'Berhasil Disimpan'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = 500;

      $sendData = [$response, 'Gagal Disimpan', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
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
      $search = $request->input('search');
      $data = Gmd::where('gmd_code', $search)
        ->orWhere('gmd_name', 'LIKE', "%$search%")
        ->get();

      if ($data && count($data) > 0) {
        $response = 200;
        $dataResult = [
          'querySearch' => $search,
          'result' => $data
        ];

        $sendData = [$response, 'Sukses', $dataResult];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (count($data) == 0) {
        // error jika data tidak ada
        $msg = "Data tidak Dapat ditemukan";
        $code = 404;
        $option = [
          "querySearch" => $search
        ];
        throw new ResponseException($msg, $code, $option);
      } else {
        // error terjadi ketika tidak ada error atapun ada kesalahan yang tidak dinginkan
        $msg = "Telah Terjadi Error Pada Server";
        $code = 500;
        throw new ResponseException($msg, $code);
      }
    } catch (ResponseException $th) {
      $response = $th->getCode();

      $sendData = [
        $th->getCode(),
        'Gagal Disimpan',
        $th->GetOptions(),
        $th->getMessage()
      ];

      return response(
        ResponseHeader::responseFailedWithData($sendData),
        $response
      );
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
      if ($data && !empty($data)) {
        $response = 200;

        $sendData = [$response, 'Sukses', $data];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (!$data) {
        $msg = "Data tidak ditemukan";
        $code = 404;
        throw new ResponseException($msg, $code);
      } else {
        $msg = "Kesalahan Pada Server";
        $code = 500;
        throw new ResponseException($msg, $code);
      }
    } catch (ResponseException $th) {
      $response = $th->getCode();

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
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
    echo $id;
    try {
      $gmd = Gmd::find($id);

      $gmd->gmd_code = strtolower($request->input('gmd_code'));
      $gmd->gmd_name = strtolower($request->input('gmd_name'));
      $gmd->save();

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $gmd];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = 500;

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
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

      $response = 200;
      $data = [
        'id' => $id
      ];

      $sendData = [$response, 'Berhasil Dihapus', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = 500;

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   * Fungsi ini untuk mengubah data sesuai keinginan admin.
   *
   * @param Request
   * @return JSON
   */
  public function updateSome(Request $request)
  {
    try {
      $data = $request->input("update");
      if ($data && count($data) > 0) {
        foreach ($data as $key => $value) {
          $result = $data[$key];
          $gmd = Gmd::find($key);
          $gmd->gmd_code = strtolower($result['gmd_code']);
          $gmd->gmd_name = strtolower($result['gmd_name']);
          $gmd->save();
        }

        $response = 200;

        $sendData = [$response, 'Berhasil Diupdate', $request->input('update')];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (count($data) < 0) {
        $msg = "Data tidak ditemukan";
        $code = 404;
        throw new ResponseException($msg, $code);
      } else {
        $msg = "Kesalahan Pada Server";
        $code = 500;
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
   * Fungsi ini berfungsi untuk mengahapus sesuai pilihan admin.
   *
   * @param Request $request
   * @return JSON
   */
  public function destroySome(Request $request)
  {
    try {
      $data = $request->input('delete');
      if ($data && count($data) > 0) {
        $data = $request->input('delete');
        foreach ($data as $id) {
          $gmd = Gmd::find($id);
          $gmd->delete();
        }

        $response = 200;
        $dataResult = [
          'id' => $data
        ];

        $sendData = [$response, 'Berhasil Dihapus', $dataResult];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (count($data) < 0) {
        $msg = "Data tidak ditemukan";
        $code = 404;
        throw new ResponseException($msg, $code);
      } else {
        $msg = "Kesalahan Pada Server";
        $code = 500;
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

  public function destroyAll()
  {
    try {
      Gmd::truncate();
      $response = 200;

      $sendData = [$response, 'Berhasil Menghapus Semua Data'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = 500;

      $sendData = [$response, 'Gagal Menghapus Semua Data', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }
}
