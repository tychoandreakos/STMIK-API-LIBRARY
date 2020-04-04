<?php

namespace App\Http\Controllers\Book;

use App\Exceptions\ResponseException;
use App\Helpers\CSV;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BookTransaction;

class BookTransactionController extends Controller
{
  private $fillable = [
    'id_book',
    'id_author',
    'id_publisher',
    'id_language',
    'id_place',
    'id_subject'
  ];

  private $validationOccurs = [
    'id_book' => 'required',
    'id_author' => 'required',
    'id_publisher' => 'required',
    'id_language' => 'required',
    'id_place' => 'required',
    'id_subject' => 'required'
  ];

  public function index(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = BookTransaction::all();
      $data = [
        "dataCount" => $dataDB->count(),
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
   * @param Request $request
   * @param BookTransaction $BookTransaction
   * @return JSON $json
   */
  public function store(Request $request)
  {
    try {
      $this->validate($request, $this->validationOccurs);
      try {
        $this->storeBookTransaction($request->all());
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
   * @param Request $request
   * @return JSON response
   */
  public function search(Request $request)
  {
    try {
      $this->validate($request, [
        'search' => 'required'
      ]);

      try {
        $search = $request->input('search');
        $data = BookTransaction::whereHas("book", function ($q) use ($search) {
          $q
            ->where('title', 'LIKE', "%$search%")
            ->orWhere('isbn', $search)
            ->orWhere("id", $search);
        })
          ->orwhereHas("author", function ($q) use ($search) {
            $q->where("name", 'LIKE', "%$search%");
          })
          ->orwhereHas("publisher", function ($q) use ($search) {
            $q->where("name", 'LIKE', "%$search%");
          })
          ->orwhereHas("language", function ($q) use ($search) {
            $q->where("name", 'LIKE', "%$search%");
          })
          ->orwhereHas("place", function ($q) use ($search) {
            $q->where("name", 'LIKE', "%$search%");
          })
          ->orwhereHas("subject", function ($q) use ($search) {
            $q->where("name", 'LIKE', "%$search%");
          })
          ->get();

        if ($data && count($data) > 0) {
          $response = 200;
          $dataResult = [
            'querySearch' => $search,
            'result' => $data
          ];

          $sendData = [$response, 'Sukses', $dataResult];
          return response(
            ResponseHeader::responseSuccess($sendData),
            $response
          );
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
          'Pencarian Telah Gagal',
          $th->GetOptions(),
          $th->getMessage()
        ];

        return response(
          ResponseHeader::responseFailedWithData($sendData),
          $response
        );
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
   * @param string $id
   * @return JSON $response
   */
  public function detail(string $id)
  {
    try {
      $data = BookTransaction::find($id);
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
   * @param int $id
   * @param Request $request
   * @return JSON $response
   */
  public function update(int $id, Request $request)
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
      $BookTransaction = $this->updateBookTransaction($id, $request);

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $BookTransaction];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * @param string $id
   * @return JSON $json
   */
  public function destroy(string $id)
  {
    try {
      $BookTransaction = BookTransaction::find($id);
      $BookTransaction->delete();

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
   * @param Request
   * @return JSON response
   */
  public function updateSome(Request $request)
  {
    try {
      $this->validate($request, [
        'update' => 'required'
      ]);

      try {
        $data = $request->input("update");
        if ($data && count($data) > 0) {
          foreach ($data as $key => $val) {
            $result = $data[$key];

            $this->updateSomeBookTransaction($key, $result);
          }

          $response = 200;

          $sendData = [
            $response,
            'Berhasil Diupdate',
            $request->input('update')
          ];
          return response(
            ResponseHeader::responseSuccess($sendData),
            $response
          );
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
   * @param Request $request
   * @return JSON response
   */
  public function destroySome(Request $request)
  {
    try {
      $this->validate($request, [
        'delete' => 'required'
      ]);

      try {
        $data = $request->input('delete');
        if ($data && count($data) > 0) {
          foreach ($data as $id) {
            $BookTransaction = BookTransaction::find($id);
            $BookTransaction->delete();
          }

          $response = 200;
          $dataResult = [
            'id' => $data
          ];

          $sendData = [$response, 'Berhasil Dihapus', $dataResult];
          return response(
            ResponseHeader::responseSuccess($sendData),
            $response
          );
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
   * @return JSON response response
   */
  public function retrieveDeleteHistoryData()
  {
    try {
      $data = BookTransaction::onlyTrashed()->get();

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
   * @param string $id
   * @return JSON response response
   */
  public function returnDeleteHistoryData(string $id)
  {
    try {
      $check = BookTransaction::find($id);
      $checkDataInSoftDelete = BookTransaction::onlyTrashed()
        ->where('id', $id)
        ->get();
      if (is_null($check) && count($checkDataInSoftDelete) < 1) {
        $msg = "BookTransaction dengan id: {$id} Tidak Dapat Ditemukan";
        $code = 400;
        throw new ResponseException($msg, $code);
      }

      BookTransaction::withTrashed()
        ->where('id', $id)
        ->restore();

      $data = BookTransaction::find($id);

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
   * @return JSON response response
   */
  public function returnAllDeleteHistoryData()
  {
    try {
      BookTransaction::onlyTrashed()->restore();

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
   * @param string $id
   * @return JSON response response
   */
  public function deleteHistoryData(string $id)
  {
    try {
      BookTransaction::withTrashed()
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
   * @return JSON response response
   */
  public function deleteAllHistoryData()
  {
    try {
      BookTransaction::onlyTrashed()->forceDelete();

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
   * @return JSON response
   */
  public function destroyAll()
  {
    try {
      BookTransaction::truncate();
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
   * Fungsi untuk melakukan export BookTransaction.
   */
  public function exportBookTransaction()
  {
    try {
      $list = BookTransaction::without('memberType')
        ->get()
        ->toArray();

      $csv = CSV::writeCSV();
      $csv->insertAll($list);
      $csv->output('member-' . time() . '.csv');

      $response = 200;

      $sendData = [$response, 'Sukses'];

      return response(
        ResponseHeader::responseSuccessWithoutData($sendData),
        $response
      );
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Mengambil Data', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * Fungsi untuk melakukan import BookTransaction pada vendor senayan.
   */
  public function importBookTransactionAnotherVendor()
  {
    $file = "senayan.csv";
    try {
      $csv = CSV::getCsv($file);

      try {
        foreach (CSV::senayanCSV($csv) as $data) {
          $this->storeBookTransaction($data);
        }

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
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Mengambil Data', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * fungsi untuk melakukan import BookTransaction
   */
  public function importBookTransaction()
  {
    $file = "users.csv";
    try {
      $csv = CSV::getCsv($file);

      try {
        foreach (CSV::structuredCsv($csv) as $data) {
          $this->storeBookTransaction($data);
        }

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
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed((int) $th->getCode());

      $sendData = [$response, 'Gagal Mengambil Data', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * @param Request $request
   * @return BookTransaction $member
   */
  private function storeBookTransaction(array $request)
  {
    $combine = array_combine($this->fillable, $request);
    return BookTransaction::create($combine);
  }

  /**
   * @param int $id
   * @param Request $request
   * @return BookTransaction $member;
   */
  private function updateBookTransaction(int $id, $request)
  {
    $BookTransaction = BookTransaction::find($id);

    foreach ($this->fillable as $column) {
      $field = $request[$column];
      if ($field != "id" && $column != "id") {
        if (strpos($field, "/") > 0 || is_numeric($field)) {
          $BookTransaction->$column = $field;
        } else {
          $BookTransaction->$column = strtolower($field);
        }
      }
    }
    return $BookTransaction->save();
  }

  /**
   * @param $key
   * @param $result
   * @return BookTransaction
   */
  private function updateSomeBookTransaction($key, $result)
  {
    $BookTransaction = BookTransaction::find($key);
    foreach ($this->fillable as $column) {
      if ($column != "id" && $column != "password") {
        $field = $result[$column];
        if (strpos($field, "/") > 0 || is_numeric($field)) {
          $BookTransaction->$column = $field;
        } else {
          $BookTransaction->$column = strtolower($field);
        }
      }
    }
    return $BookTransaction->save();
  }
}
