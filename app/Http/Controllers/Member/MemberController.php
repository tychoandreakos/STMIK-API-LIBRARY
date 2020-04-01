<?php

namespace App\Http\Controllers\Member;

use App\Exceptions\ResponseException;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Member;
use App\MemberType;
use Illuminate\Support\Facades\Crypt;

class MemberController extends Controller
{
  private $fillable = [
    'id',
    'membertype_id',
    'name',
    'birthdate',
    "member_since",
    'expiry_date',
    'alamat',
    'username',
    'email',
    'password',
    'phone',
    'pending',
    'image'
  ];

  private $validationStore = [
    'id' => 'required|unique:member|integer',
    'password' => 'required|string'
  ];

  private $validationOccurs = [
    'membertype_id' => 'required|integer',
    'name' => 'required|string|max:150',
    'birthdate' => 'nullable',
    'member_since' => 'required|date',
    'expiry_date' => 'required|date',
    'alamat' => 'nullable|string',
    'username' => 'required|string',
    'email' => 'required|email',
    'phone' => 'required|string',
    'pending' => 'nullable',
    'image' => 'nullable'
  ];

  public function index(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = Member::all();
      $data = [
        "dataCount" => $dataDB->count(),
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
   * @param Request $request
   * @param Member $Member
   * @return JSON $json
   */
  public function store(Request $request)
  {
    try {
      $this->validate(
        $request,
        array_merge($this->validationStore, $this->validationOccurs)
      );

      try {
        $this->storeMember($request);
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
        $find = Member::where('name', 'LIKE', "%$search%")->orWhere(
          'id',
          $search
        );
        if ($find->get() && count($find->get()) > 0) {
          $data = $find->get();
        } else {
          try {
            $data = $find
              ->orWhere('birthdate', $search)
              ->orWhere('member_since', $search)
              ->orWhere('expiry_date', $search)
              ->get();
          } catch (\Throwable $th) {
            $data = MemberType::with('member')
              ->where('name', 'LIKE', "%$search%")
              ->select('id')
              ->get();
          }
        }
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
   * @param int $id
   * @return JSON $response
   */
  public function detail(int $id)
  {
    try {
      $data = Member::find($id);
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
      $Member = $this->updateMember($id, $request);

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $Member];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * @param Int $id
   * @return JSON $json
   */
  public function destroy(int $id)
  {
    try {
      $Member = Member::find($id);
      $Member->delete();

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

            $this->updateSomeMember($key, $result);
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
            $Member = Member::find($id);
            $Member->delete();
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
      $data = Member::onlyTrashed()->get();

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
   * @param int $id
   * @return JSON response response
   */
  public function returnDeleteHistoryData(int $id)
  {
    try {
      $check = Member::find($id);
      $checkDataInSoftDelete = Member::onlyTrashed()
        ->where('id', $id)
        ->get();
      if (is_null($check) && count($checkDataInSoftDelete) < 1) {
        $msg = "Member dengan id: {$id} Tidak Dapat Ditemukan";
        $code = 400;
        throw new ResponseException($msg, $code);
      }

      Member::withTrashed()
        ->where('id', $id)
        ->restore();

      $data = Member::find($id);

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
      Member::onlyTrashed()->restore();

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
  public function deleteHistoryData(int $id)
  {
    try {
      Member::withTrashed()
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
      Member::onlyTrashed()->forceDelete();

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
      Member::truncate();
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

  public function exportMember()
  {
    $list = Member::without('memberType')
      ->get()
      ->toArray();
    $fp = fopen("member-" . time() . ".csv", 'w');

    foreach ($list as $fields) {
      fputcsv($fp, $fields);
    }
    fclose($fp);
  }

  /**
   * @param String $file
   */
  public function importMember(string $file)
  {
    $memberCount = Member::count();
    $row = 1;
    if (($handle = fopen($file, "r")) !== false) {
      while (($data = fgetcsv($handle, $memberCount, ",", '"')) !== false) {
        $num = count($data);
        $row++;
        for ($c = 0; $c < $num; $c++) {
          echo $data[$c] . "<br />\n";
        }
      }
      fclose($handle);
    }
  }

  /**
   * @param Request $request
   * @return Member $member
   */
  private function storeMember($request)
  {
    $combine = array_combine($this->fillable, $request->all());
    $combine['password'] = Crypt::encrypt($combine['password']);
    $Member = Member::create($combine);
    return $Member->save();
  }

  /**
   * @param int $id
   * @param Request $request
   * @return Member $member;
   */
  private function updateMember(int $id, $request)
  {
    $Member = Member::find($id);

    foreach ($this->fillable as $column) {
      $field = $request[$column];
      if ($field != "id" && $column != "id") {
        if (strpos($field, "/") > 0 || is_numeric($field)) {
          $Member->$column = $field;
        } else {
          $Member->$column = strtolower($field);
        }
      }
    }
    return $Member->save();
  }

  /**
   * @param $key
   * @param $result
   * @return Member
   */
  private function updateSomeMember($key, $result)
  {
    $Member = Member::find($key);
    foreach ($this->fillable as $column) {
      if ($column != "id" && $column != "password") {
        $field = $result[$column];
        if (strpos($field, "/") > 0 || is_numeric($field)) {
          $Member->$column = $field;
        } else {
          $Member->$column = strtolower($field);
        }
      }
    }
    return $Member->save();
  }
}
