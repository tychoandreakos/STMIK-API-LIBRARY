<?php

namespace App\Http\Controllers\Book;

use App\Exceptions\ResponseException;
use App\Helpers\CSV;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Biblio;
use App\Book;
use App\BookTransaction;
use App\Helpers\BibliobigrafiRelationship;

class BiblioController extends Controller
{
  private $fillable = [
    'pattern_id',
    'id_book_transaction',
    'id_pattern',
    "id_classification",
    'id_location',
    'id_gmd',
    'id_koleksi',
    'id_item_status'
  ];

  private $modifiedRequest = [];

  private $additional = [
    'id_pattern' => 'required',
    "id_classification" => 'required',
    'id_location' => 'required',
    'id_gmd' => 'required',
    'id_koleksi' => 'required',
    'id_item_status' => 'required'
  ];

  private $additionBook = [
    'id_author' => 'required',
    'id_publisher' => 'required',
    'id_language' => 'required',
    'id_place' => 'required',
    'id_subject' => 'required'
  ];

  private $validationOccurs = [
    'title' => 'required|string',
    'edition' => 'required',
    'isbn' => 'required',
    'release_date' => 'required',
    'length' => 'nullable',
    'file_image' => 'nullable',
    'file_name' => 'nullable',
    'file_size' => 'nullable',
    'description' => 'nullable',
    'count' => 'required'
  ];

  public function index(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = Biblio::latest()->get();
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
   * @param Biblio $Biblio
   * @return JSON $json
   */
  public function store(Request $request)
  {
    try {
      $this->validate(
        $request,
        array_merge(
          $this->validationOccurs,
          $this->additionBook,
          $this->additional
        )
      );
      try {
        $this->bookTransaction($request->all());
        $count = $request->count;
        for ($i = 0; $i < $count; $i++) {
          $this->storeBiblio($this->modifiedRequest);
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
        $data = Biblio::where('pattern_id', 'LIKE', "%$search%")
          ->orWhereHas('classification', function ($q) use ($search) {
            $q->where("id", $search)->orWhere("name", 'LIKE', "%$search%");
          })
          ->orWhereHas('location', function ($q) use ($search) {
            $q
              ->where("name", 'LIKE', "%$search%")
              ->orWhere("code", 'LIKE', "%$search%");
          })
          ->orWhereHas('gmd', function ($q) use ($search) {
            $q
              ->where("gmd_name", 'LIKE', "%$search%")
              ->orWhere("gmd_code", 'LIKE', "%$search%");
          })
          ->orWhereHas('koleksi', function ($q) use ($search) {
            $q->where("tipe", 'LIKE', "%$search%");
          })
          ->orWhereHas('itemStatus', function ($q) use ($search) {
            $q
              ->where("name", 'LIKE', "%$search%")
              ->orWhere("code", 'LIKE', "%$search%");
          })
          ->orWhereHas('bookTransaction', function ($q) use ($search) {
            $q
              ->whereHas("book", function ($q) use ($search) {
                $q
                  ->where('title', 'LIKE', "%$search%")
                  ->orWhere("isbn", $search)
                  ->orWhere("id", $search);
              })
              ->orWhereHas("author", function ($q) use ($search) {
                $q->where("name", 'LIKE', "%$search");
              })
              ->orWhereHas("publisher", function ($q) use ($search) {
                $q->where("name", 'LIKE', "%$search");
              })
              ->orWhereHas("language", function ($q) use ($search) {
                $q->where("name", 'LIKE', "%$search");
              })
              ->orWhereHas("subject", function ($q) use ($search) {
                $q->where("type", 'LIKE', "%$search");
              })
              ->orWhereHas("place", function ($q) use ($search) {
                $q->where("name", 'LIKE', "%$search");
              });
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
      $data = Biblio::find($id);
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
   * @param string $id
   * @param Request $request
   * @return JSON $response
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
      $Biblio = $this->updateBiblio($id, $request);

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $Biblio];
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
      $Biblio = Biblio::find($id);
      $Biblio->delete();

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

            $this->updateSomeBiblio($key, $result);
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
            $Biblio = Biblio::find($id);
            $Biblio->delete();
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
      $data = Biblio::onlyTrashed()->get();

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
      $check = Biblio::find($id);
      $checkDataInSoftDelete = Biblio::onlyTrashed()
        ->where('id', $id)
        ->get();
      if (is_null($check) && count($checkDataInSoftDelete) < 1) {
        $msg = "Biblio dengan id: {$id} Tidak Dapat Ditemukan";
        $code = 400;
        throw new ResponseException($msg, $code);
      }

      Biblio::withTrashed()
        ->where('id', $id)
        ->restore();

      $data = Biblio::find($id);

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
      Biblio::onlyTrashed()->restore();

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
      Biblio::withTrashed()
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
      Biblio::onlyTrashed()->forceDelete();

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
      Biblio::truncate();
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
   * Fungsi untuk melakukan export Biblio.
   */
  public function exportBiblio()
  {
    try {
      $list = Biblio::without('memberType')
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
   * Fungsi untuk melakukan import Biblio pada vendor senayan.
   */
  public function importBiblioAnotherVendor()
  {
    $file = "senayan.csv";
    try {
      $csv = CSV::getCsv($file);

      try {
        foreach (CSV::senayanCSV($csv) as $data) {
          $this->storeBiblio($data);
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
   * fungsi untuk melakukan import Biblio
   */
  public function importBiblio()
  {
    $file = "users.csv";
    try {
      $csv = CSV::getCsv($file);

      try {
        foreach (CSV::structuredCsv($csv) as $data) {
          $this->storeBiblio($data);
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
   * @return Biblio $member
   */
  private function storeBiblio(array $request)
  {
    $request["pattern_id"] = $this->relationshipBiblio($request['id_pattern']);
    $combine = array_combine($this->fillable, $request);
    return Biblio::create($combine);
  }

  /**
   *
   */
  private function bookTransaction(array $request)
  {
    $bookKey = [
      "title",
      "edition",
      "isbn",
      "release_date",
      "length",
      "file_image",
      "file_name",
      "file_size",
      'description'
    ];
    $bookSave = [];
    foreach ($bookKey as $key) {
      $bookSave[$key] = $request[$key];
    }
    $book = Book::create($bookSave);
    $bookTransactionKey = [
      'id_author',
      'id_publisher',
      'id_language',
      'id_place',
      'id_subject'
    ];
    $bookEntity = ["id_book" => $book->id];
    foreach ($bookTransactionKey as $key) {
      $bookEntity[$key] = $request[$key];
    }

    $bookTransaction = BookTransaction::create($bookEntity);

    $this->modifiedRequest = [
      'pattern_id' => "",
      'id_book_transaction' => $bookTransaction->id,
      'id_pattern' => $request['id_pattern'],
      'id_classification' => $request['id_classification'],
      'id_location' => $request['id_location'],
      'id_gmd' => $request['id_gmd'],
      'id_koleksi' => $request['id_koleksi'],
      'id_item_status' => $request['id_item_status']
    ];
  }

  /**
   *
   */
  private function relationshipBiblio($combine)
  {
    $pattern = new BibliobigrafiRelationship();
    $pattern->modifyPattern($combine);
    return $pattern->getPattern();
  }

  /**
   * @param int $id
   * @param Request $request
   * @return Biblio $member;
   */
  private function updateBiblio(int $id, $request)
  {
    $Biblio = Biblio::find($id);

    foreach ($this->fillable as $column) {
      $field = $request[$column];
      if ($field != "id" && $column != "id") {
        if (strpos($field, "/") > 0 || is_numeric($field)) {
          $Biblio->$column = $field;
        } else {
          $Biblio->$column = strtolower($field);
        }
      }
    }
    return $Biblio->save();
  }

  /**
   * @param $key
   * @param $result
   * @return Biblio
   */
  private function updateSomeBiblio($key, $result)
  {
    $Biblio = Biblio::find($key);
    foreach ($this->fillable as $column) {
      if ($column != "id" && $column != "password") {
        $field = $result[$column];
        if (strpos($field, "/") > 0 || is_numeric($field)) {
          $Biblio->$column = $field;
        } else {
          $Biblio->$column = strtolower($field);
        }
      }
    }
    return $Biblio->save();
  }
}
