<?php

namespace App\Http\Controllers\Master;

use App\Subject;
use App\Exceptions\ResponseException;
use App\Helpers\ControllerHelper;
use Illuminate\Http\Request;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;
use App\Http\Controllers\Controller as Controller;

class SubjectController extends Controller
{
  private $fillable = ['name', 'type'];

  private $validationOccurs = [
    'name' => 'required',
    'type' => 'required'
  ];

  /**
   * Fungsi ini berfungsi untuk mendapakatkan data dari database. Response yang diterima
   * adalah seluruh data Subject.
   *
   * @return JSON response $json;
   */
  public function index(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = Subject::latest()->get();
      $data = [
        'dataCount' => $dataDB->count(),
        'result' => $dataDB->skip($skip)->take($take)
      ];

      $response = 200;

      $sendData = [$response, 'Sukses', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * Ini fungsi untuk menyimpan data subject kedalam database menggunakan
   * class Request & subject sebagai Param
   * @param $subject
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
      $this->storeSubject($request->all());

      $response = 201;

      $sendData = [$response, 'Berhasil Disimpan'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

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
      $data = Subject::where('name', $search)->get();
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
   *  Fungsi atau method ini berguna untuk menampilkan detail item Subject.
   *
   * @param String $id
   * @return JSON response;
   */
  public function detail(string $id)
  {
    try {
      $data = Subject::find($id);
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
   * Fungsi ini bertugas untuk mengupdate data yang ada didalam database subject.
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
      $subject = $this->updateSubject($request->all(), $id);

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $subject];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

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
      $subject = Subject::find($id);
      $subject->delete();

      $response = 200;
      $data = [
        'id' => $id
      ];

      $sendData = [$response, 'Berhasil Dihapus', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

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
        'update' => 'required'
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
      $data = $request->input('update');
      if ($data && count($data) > 0) {
        foreach ($data as $key => $value) {
          $result = $data[$key];
          $Subject = Subject::find($key);
          $updateHelper->update($Subject, $this->fillable, $result);
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
          $subject = Subject::find($id);
          $subject->delete();
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
   * Fungsin ini berguna untuk menampilkkan detail item Subject berupa koleksi
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
            $tempData[] = Subject::find($id);
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
          Subject::withTrashed()
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
          Subject::withTrashed()
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
   * Fungsi ini berfungsi untuk memunculkan data yang sudah terhapus dengan method softDelete.
   *
   * @return JSON response response
   */
  public function retrieveDeleteHistoryData(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = Subject::onlyTrashed()
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
   * Fungsi ini berfungsi untuk mengembalikan data yang sudah terhapus dengan method softDelete.
   *
   * @return JSON response response
   */
  public function returnDeleteHistoryData(string $id)
  {
    try {
      $check = Subject::find($id);
      $checkDataInSoftDelete = Subject::onlyTrashed()
        ->where('id', $id)
        ->get();
      if (is_null($check) && count($checkDataInSoftDelete) < 1) {
        $msg = "Id: {$id} Tidak Dapat Ditemukan";
        $code = 400;
        throw new ResponseException($msg, $code);
      }

      Subject::withTrashed()
        ->where('id', $id)
        ->restore();

      $data = Subject::find($id);

      $response = 200;

      $sendData = [$response, 'Berhasil Dikembalikan', $data];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

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
      Subject::onlyTrashed()->restore();

      $response = 200;

      $sendData = [$response, 'Berhasil Mengembalikan Semua'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

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
      Subject::withTrashed()
        ->where('id', $id)
        ->forceDelete();

      $response = 200;

      $sendData = [$response, 'Sukses Dihapus'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
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
      Subject::onlyTrashed()->forceDelete();

      $response = 200;

      $sendData = [$response, 'Sukses Dihapus'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

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
      Subject::truncate();
      $response = 200;

      $sendData = [$response, 'Berhasil Menghapus Semua Data'];
      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

      $sendData = [$response, 'Gagal Menghapus Semua Data', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   *
   */
  public function searchDataForDropdown(Request $request)
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
      $data = Subject::where('name', 'LIKE', "%$search%")->get();
      if ($data && count($data) > 0) {
        $response = 200;

        $data = Subject::where('gmd_code', $search)
          ->orWhere('gmd_name', 'LIKE', "%$search%")
          ->select('id', 'gmd_name')
          ->orderBy('gmd_name')
          ->get();
        $dataDB = [];

        foreach ($data as $temp) {
          $dataDB[$temp->id] = [
            'id' => $temp->id,
            'name' => $temp->gmd_name
          ];
        }

        $dataResult = [
          'querySearch' => $search,
          'length' => count($data),
          'result' => $dataDB
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
   *
   */
  public function getDataForDropdown()
  {
    try {
      // $skip = Pagination::skip($request->input('skip')); //
      // $take = Pagination::take($request->input('take'));

      $data = Subject::select('id', 'name')
        ->orderBy('name')
        ->get();
      $dataDB = [];

      foreach ($data->skip(0)->take(35) as $temp) {
        $dataDB[$temp->id] = [
          'id' => $temp->id,
          'name' => $temp->name
        ];
      }

      $data = [
        'dataCount' => $data->count(),
        'result' => $dataDB
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
   */
  private function storeSubject(array $request)
  {
    $combine = array_combine($this->fillable, $request);
    return Subject::create($combine);
  }

  /**
   *
   */
  private function updateSubject(array $request, $id)
  {
    $combine = array_combine($this->fillable, $request);
    Subject::find($id)->update($combine);

    return $combine;
  }
}
