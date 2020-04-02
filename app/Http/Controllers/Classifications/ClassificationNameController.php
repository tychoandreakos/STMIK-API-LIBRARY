<?php

namespace App\Http\Controllers\Classifications;

use App\Exceptions\ResponseException;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ClassificationName;

class ClassificationNameController extends Controller
{
  private $fillable = ['name'];

  private $validationOccurs = [
    'name' => 'required|string|max:150'
  ];

  public function index(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = ClassificationName::all()->sort();
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
   * @param ClassificationName $ClassificationName
   * @return JSON $json
   */
  public function store(Request $request)
  {
    try {
      $this->validate($request, $this->validationOccurs);

      try {
        $this->storeClassificationName($request->all());
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
        $data = ClassificationName::where('name', 'LIKE', "%$search%")->get();

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
      $ClassificationName = $this->updateClassificationName($id, $request);

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $ClassificationName];
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
      $ClassificationName = ClassificationName::find($id);
      $ClassificationName->delete();

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
   * @param Request $request
   * @return ClassificationName $member
   */
  private function storeClassificationName(array $request)
  {
    $combine = array_combine($this->fillable, $request);
    return ClassificationName::create($combine);
  }

  /**
   * @param int $id
   * @param Request $request
   * @return ClassificationName $member;
   */
  private function updateClassificationName(int $id, $request)
  {
    $ClassificationName = ClassificationName::find($id);

    foreach ($this->fillable as $column) {
      $field = $request[$column];
      if ($field != "id" && $column != "id") {
        if (strpos($field, "/") > 0 || is_numeric($field)) {
          $ClassificationName->$column = $field;
        } else {
          $ClassificationName->$column = strtolower($field);
        }
      }
    }
    return $ClassificationName->save();
  }
}
