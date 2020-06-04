<?php

namespace App\Http\Controllers\Member;

use App\MemberType;
use App\Exceptions\ResponseException;
use App\Helpers\ControllerHelper;
use Illuminate\Http\Request;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;
use App\Http\Controllers\Controller as Controller;

class MemberTypeController extends Controller
{
  private $fillable = [
    'name',
    'limit_loan',
    'loan_periode',
    'membership_periode',
    'fines'
  ];

  private $validationOccurs = [
    'name' => 'required',
    'limit_loan' => 'required',
    'loan_periode' => 'required',
    'membership_periode' => 'required',
    'fines' => 'required'
  ];

  /**
   * Fungsi ini berfungsi untuk mendapakatkan data dari database. Response yang diterima
   * adalah seluruh data Member Type.
   *
   * @return JSON response $json;
   */
  public function index(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = MemberType::latest()->get();

      $data = [
        'dataCount' => $dataDB->count(),
        'result' => $dataDB->skip($skip)->take($take)
      ];

      $response = 200;

      $sendData = [$response, 'Sukses', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * Ini fungsi untuk menyimpan data memberType kedalam database menggunakan
   * class Request & memberType sebagai Param
   * @param $memberType
   * @param $request
   * @return JSON response json
   */
  public function store(Request $request)
  {
    try {
      $this->validate($request, $this->validationOccurs);
    } catch (\Throwable $th) {
      $response = 400;

      $sendData = [
        $response,
        'Harap Masukan Data Yang Valid',
        $th->getMessage()
      ];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
    try {
      $this->storeMemberType($request->all());

      $response = 201;

      $sendData = [$response, 'Berhasil Disimpan'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed(
        (int) (int) $th->getCode()
      );

      $sendData = [$response, 'Gagal Disimpan', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   * Fungsi ini bertugas untuk mencari data sesuai dengan parameters yang sudah diinginkan,
   *
   * @param Request $request;
   * @return JSON response $json
   */
  public function search(Request $request)
  {
    try {
      $this->validate($request, [
        'search' => 'required'
      ]);
    } catch (\Throwable $th) {
      $response = 400;

      $sendData = [
        $response,
        'Harap Masukan Data Yang Valid',
        $th->getMessage()
      ];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }

    try {
      $search = $request->input('search');
      $data = MemberType::where('name', 'LIKE', "%$search%")
        ->orWhere('fines', 'LIKE', "%$search%")
        ->get();
      if ($data && count($data) > 0) {
        $response = 200;
        $dataResult = [
          'querySearch' => $search,
          'length' => count($data),
          'result' => $data
        ];

        $sendData = [$response, 'Sukses', $dataResult];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (count($data) == 0) {
        // error jika data tidak ada
        $msg = 'Data tidak Dapat ditemukan';
        $code = 404;
        $option = [
          'querySearch' => $search
        ];
        throw new ResponseException($msg, $code, $option);
      } else {
        // error terjadi ketika tidak ada error atapun ada kesalahan yang tidak dinginkan
        $msg = 'Telah Terjadi Error Pada Server';
        $code = 500;
        throw new ResponseException($msg, $code);
      }
    } catch (ResponseException $th) {
      $response = $th->getCode();

      $sendData = [
        $th->getCode(),
        'Pencarian Dibatalkan',
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
   * Fungsin ini berguna untuk menampilkkan detail item Member Type berupa koleksi
   *
   * @param Request $request
   * @return JSON response;
   */
  public function multipleDetail(Request $request)
  {
    try {
      $this->validate($request, [
        'detail' => 'required'
      ]);

      try {
        $data = $request->input('detail');
        $tempData = [];
        if ($data && count($data) > 0) {
          foreach ($data as $id) {
            $tempData[] = MemberType::find($id);
          }
          $response = 200;

          $sendData = [$response, 'Berhasil Diambil', $tempData];
          return response(
            ResponseHeader::responseSuccess($sendData),
            $response
          );
        } elseif (count($data) < 0) {
          $msg = 'Data tidak ditemukan';
          $code = 404;
          throw new ResponseException($msg, $code);
        } else {
          $msg = 'Kesalahan Pada Server';
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
    } catch (\Throwable $th) {
      $response = 400;

      $sendData = [
        $response,
        'Harap Masukan Data Yang Valid',
        $th->getMessage()
      ];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *  Fungsi atau method ini berguna untuk menampilkan detail item Member Type.
   *
   * @param String $id
   * @return JSON response;
   */
  public function detail(string $id)
  {
    try {
      $data = MemberType::find($id);
      if ($data && !empty($data)) {
        $response = 200;

        $sendData = [$response, 'Sukses', $data];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (!$data) {
        $msg = 'Data tidak ditemukan';
        $code = 404;
        throw new ResponseException($msg, $code);
      } else {
        $msg = 'Kesalahan Pada Server';
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
   * Fungsi ini bertugas untuk mengupdate data yang ada didalam database memberType.
   * Data yang diubah sesuai dengan $id dalam parameter yang diberikan
   *
   * @param String $id,
   * @param Request $request
   * @return JSON response $reponse;
   */
  public function update(string $id, Request $request)
  {
    try {
      $this->validate($request, $this->validationOccurs);
    } catch (\Throwable $th) {
      $response = 400;

      $sendData = [
        $response,
        'Gagal Validasi atau Data Yang Anda Cari Tidak Ada',
        $th->getMessage()
      ];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }

    try {
      $memberType = $this->updateMemberType($request->all(), $id);

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $memberType];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed(
        (int) (int) $th->getCode()
      );

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * Fungsi ini bertugas untuk menghapus data yang ada didalam database menggunakan methode Hard Delete.
   *
   * @param String $id
   * @return JSON response $json
   */
  public function destroy(string $id)
  {
    try {
      $memberType = MemberType::find($id);
      $memberType->delete();

      $response = 200;
      $data = [
        'id' => $id
      ];

      $sendData = [$response, 'Berhasil Dihapus', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   * Fungsi ini untuk mengubah data sesuai keinginan admin.
   *
   * @param Request $request
   * @param ControllerHelpers $updateHelper
   * @return JSON response
   */
  public function updateSome(ControllerHelper $updateHelper, Request $request)
  {
    try {
      $this->validate($request, [
        'updates' => 'required'
      ]);
    } catch (\Throwable $th) {
      $response = 400;

      $sendData = [
        $response,
        'Harap Masukan Data Yang Valid',
        $th->getMessage()
      ];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }

    try {
      $data = $request->input('updates');
      if ($data && count($data) > 0) {
        foreach ($data as $key => $value) {
          $result = $data[$key];
          $MemberType = MemberType::find($key);
          $updateHelper->update($MemberType, $this->fillable, $result);
        }

        $response = 200;

        $sendData = [$response, 'Berhasil Diupdate', $request->input('update')];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (count($data) < 0) {
        $msg = 'Data tidak ditemukan';
        $code = 404;
        throw new ResponseException($msg, $code);
      } else {
        $msg = 'Kesalahan Pada Server';
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
   * @return JSON response
   */
  public function destroySome(Request $request)
  {
    try {
      $this->validate($request, [
        'delete' => 'required'
      ]);
    } catch (\Throwable $th) {
      $response = 400;

      $sendData = [
        $response,
        'Harap Masukan Data Yang Valid',
        $th->getMessage()
      ];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }

    try {
      $data = $request->input('delete');
      if ($data && count($data) > 0) {
        foreach ($data as $id) {
          $memberType = MemberType::find($id);
          $memberType->delete();
        }

        $response = 200;
        $dataResult = [
          'id' => $data
        ];

        $sendData = [$response, 'Berhasil Dihapus', $dataResult];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (count($data) < 0) {
        $msg = 'Data tidak ditemukan';
        $code = 404;
        throw new ResponseException($msg, $code);
      } else {
        $msg = 'Kesalahan Pada Server';
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
   * Fungsi ini berfungsi untuk memunculkan data yang sudah terhapus dengan method softDelete.
   *
   * @return JSON response response
   */
  public function retrieveDeleteHistoryData(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = MemberType::onlyTrashed()
        ->latest()
        ->get();

      $data = [
        'dataCount' => $dataDB->count(),
        'result' => $dataDB->skip($skip)->take($take)
      ];

      $response = 200;

      $sendData = [$response, 'Sukses', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   * Fungsi ini berkerja untuk mengembalikan data sesuai pilihan user / admin.
   *
   * @param Request $request
   * @return JSON $response
   */

  public function restoreCollectionData(Request $request)
  {
    try {
      $this->validate($request, [
        'restore' => 'required'
      ]);
    } catch (\Throwable $th) {
      $response = 400;

      $sendData = [
        $response,
        'Harap Masukan Data Yang Valid',
        $th->getMessage()
      ];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }

    try {
      $data = $request->input('restore');
      if ($data && count($data) > 0) {
        foreach ($data as $key => $value) {
          $result = $data[$key];
          MemberType::withTrashed()
            ->where('id', $result)
            ->restore();
        }

        $response = 200;

        $sendData = [$response, 'Berhasil Diupdate', $request->input('update')];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (count($data) < 0) {
        $msg = 'Data tidak ditemukan';
        $code = 404;
        throw new ResponseException($msg, $code);
      } else {
        $msg = 'Kesalahan Pada Server';
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
   * Fungsi ini berfungsi untuk mengembalikan data yang sudah terhapus dengan method softDelete.
   *
   * @param String $id
   * @return JSON response response
   */
  public function returnDeleteHistoryData(string $id)
  {
    try {
      $check = MemberType::find($id);
      $checkDataInSoftDelete = MemberType::onlyTrashed()
        ->where('id', $id)
        ->get();
      if (is_null($check) && count($checkDataInSoftDelete) < 1) {
        $msg = "Id: {$id} Tidak Dapat Ditemukan";
        $code = 400;
        throw new ResponseException($msg, $code);
      }

      MemberType::withTrashed()
        ->where('id', $id)
        ->restore();

      $data = MemberType::find($id);

      $response = 200;

      $sendData = [$response, 'Berhasil Dikembalikan', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   * Fungsi ini berfungsi untuk mengembalikan semua data yang sudah terhapus dengan method softDelete.
   *
   * @return JSON response response
   */
  public function returnAllDeleteHistoryData()
  {
    try {
      MemberType::onlyTrashed()->restore();

      $response = 200;

      $sendData = [$response, 'Berhasil Mengembalikan Semua'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   * Fungsi ini berfungsi untuk menghapus data yang sudah terhapus dengan method softDelete.
   *
   * @param string $id
   * @return JSON response response
   */
  public function deleteHistoryData(string $id)
  {
    try {
      MemberType::withTrashed()
        ->where('id', $id)
        ->forceDelete();

      $response = 200;

      $sendData = [$response, 'Sukses Dihapus'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * Fungsi ini bertugas untuk mengahapus data bertipe koleksi.
   *
   * @param Request $request
   * @return JSON ersponse response
   */
  public function deleteHistoryCollectionData(Request $request)
  {
    try {
      $this->validate($request, [
        'delete' => 'required'
      ]);
    } catch (\Throwable $th) {
      $response = 400;

      $sendData = [
        $response,
        'Harap Masukan Data Yang Valid',
        $th->getMessage()
      ];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }

    try {
      $data = $request->input('delete');
      if ($data && count($data) > 0) {
        foreach ($data as $key => $value) {
          $result = $data[$key];
          MemberType::withTrashed()
            ->where('id', $result)
            ->forceDelete();
        }

        $response = 200;

        $sendData = [$response, 'Berhasil Diupdate', $request->input('update')];
        return response(ResponseHeader::responseSuccess($sendData), $response);
      } elseif (count($data) < 0) {
        $msg = 'Data tidak ditemukan';
        $code = 404;
        throw new ResponseException($msg, $code);
      } else {
        $msg = 'Kesalahan Pada Server';
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
   * Fungsi ini berfungsi untuk menghapus semua data yang sudah terhapus dengan method softDelete.
   *
   * @return JSON response response
   */
  public function deleteAllHistoryData()
  {
    try {
      MemberType::onlyTrashed()->forceDelete();

      $response = 200;

      $sendData = [$response, 'Sukses Dihapus'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   * Fungsi ini berfungsi untuk menghapus seluruh data
   *
   * @return JSON response
   */
  public function destroyAll()
  {
    try {
      MemberType::truncate();
      $response = 200;

      $sendData = [$response, 'Berhasil Menghapus Semua Data'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Menghapus Semua Data', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   */
  private function storeMemberType(array $request)
  {
    $combine = array_combine($this->fillable, $request);
    return MemberType::create($combine);
  }

  /**
   *
   */
  private function updateMemberType(array $request, $id)
  {
    $combine = array_combine($this->fillable, $request);
    MemberType::find($id)->update($combine);

    return $combine;
  }
}
