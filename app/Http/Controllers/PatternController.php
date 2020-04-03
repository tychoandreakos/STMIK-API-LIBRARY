<?php

namespace App\Http\Controllers;
use App\Exceptions\ResponseException;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;
use Illuminate\Http\Request;
use App\Pattern;

class PatternController extends Controller
{
  private $fillable = ['prefix', 'suffix', 'middle', 'last_pattern'];

  private $validationOccurs = [
    'prefix' => 'required|string',
    'suffix' => 'required|string',
    'middle' => 'required|string',
    'last_pattern' => 'string'
  ];

  public function index(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = Pattern::all()->sort();
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
   * @param Pattern $Pattern
   * @return JSON $json
   */
  public function store(Request $request)
  {
    try {
      $this->validate($request, $this->validationOccurs);

      try {
        $this->storePattern($request->all());
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
        $data = Pattern::where('last_pattern', 'LIKE', "%$search%")
          ->orWhere('suffix', $search)
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
      $Pattern = $this->updatePattern($id, $request);

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $Pattern];
      return response(ResponseHeader::responseSuccess($sendData), $response);
    } catch (\Throwable $th) {
      $response = ResponseHeader::responseStatusFailed($th->getCode());

      $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
      return response(ResponseHeader::responseFailed($sendData), $response);
    }
  }

  /**
   * Update last_pattern row
   */
  public function updateLastPattern($pattern)
  {
    try {
      $pattern = Pattern::find($pattern[0]);
      $pattern->last_patern = $pattern[1];
      $pattern->save();

      $response = 200;

      $sendData = [$response, 'Berhasil Diubah', $pattern];
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
      $Pattern = Pattern::find($id);
      $Pattern->delete();

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
   * @return Pattern $member
   */
  private function storePattern(array $request)
  {
    $combine = array_combine($this->fillable, $request);
    return Pattern::create($combine);
  }

  /**
   * @param int $id
   * @param Request $request
   * @return Pattern $member;
   */
  private function updatePattern(int $id, $request)
  {
    $Pattern = Pattern::find($id);

    foreach ($this->fillable as $column) {
      $field = $request[$column];
      if ($field != "id" && $column != "id") {
        if (strpos($field, "/") > 0 || is_numeric($field)) {
          $Pattern->$column = $field;
        } else {
          $Pattern->$column = strtolower($field);
        }
      }
    }
    return $Pattern->save();
  }
}
