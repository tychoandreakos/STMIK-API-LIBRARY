<?php

namespace App\Http\Controllers\Member;

use App\Exceptions\ResponseException;
use App\Helpers\Pagination;
use App\Helpers\ResponseHeader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Member;

class MemberController extends Controller
{
  public function index(Request $request)
  {
    try {
      $skip = Pagination::skip($request->input('skip')); //
      $take = Pagination::take($request->input('take'));

      $dataDB = Member::with([
        'memberType' => function ($query) {
          $query->select(
            'id',
            'name',
            'limit_loan',
            'loan_periode',
            'membership_periode'
          );
        }
      ])->get();
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
   * @param Member $memberType
   * @return JSON $json
   */
  public function store(Member $memberType, Request $request)
  {
    try {
      $this->validate($request, [
        'id' => 'required|unique:member|integer',
        'membertype_id' => 'required|integer',
        'name' => 'required|string|max:150',
        'birthdate' => 'nullable',
        'member_since' => 'required|date',
        'expiry_date' => 'required|date'
      ]);

      try {
        $memberType->id = $request->id;
        $memberType->membertype_id = $request->membertype_id;
        $memberType->name = strtolower($request->name);
        $memberType->birthdate = $request->birthdate;
        $memberType->member_since = $request->member_since;
        $memberType->expiry_date = $request->expiry_date;

        $memberType->save();
        $response = 201;

        $sendData = [$response, 'Berhasil Disimpan'];

        return response(
          ResponseHeader::responseSuccessWithoutData($sendData),
          $response
        );
      } catch (\Throwable $th) {
        $response = ResponseHeader::responseStatusFailed($th->getCode());

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

  //   /**
  //    * @param Request $request
  //    * @return JSON response
  //    */
  //   public function search(Request $request)
  //   {
  //     try {
  //       $this->validate($request, [
  //         'search' => 'required'
  //       ]);

  //       try {
  //         $search = $request->input('search');
  //         $data = Member::where('name', $search)->get();
  //         if ($data && count($data) > 0) {
  //           $response = 200;
  //           $dataResult = [
  //             'querySearch' => $search,
  //             'result' => $data
  //           ];

  //           $sendData = [$response, 'Sukses', $dataResult];
  //           return response(
  //             ResponseHeader::responseSuccess($sendData),
  //             $response
  //           );
  //         } elseif (count($data) == 0) {
  //           // error jika data tidak ada
  //           $msg = "Data tidak Dapat ditemukan";
  //           $code = 404;
  //           $option = [
  //             "querySearch" => $search
  //           ];
  //           throw new ResponseException($msg, $code, $option);
  //         } else {
  //           // error terjadi ketika tidak ada error atapun ada kesalahan yang tidak dinginkan
  //           $msg = "Telah Terjadi Error Pada Server";
  //           $code = 500;
  //           throw new ResponseException($msg, $code);
  //         }
  //       } catch (ResponseException $th) {
  //         $response = $th->getCode();

  //         $sendData = [
  //           $th->getCode(),
  //           'Pencarian Telah Gagal',
  //           $th->GetOptions(),
  //           $th->getMessage()
  //         ];

  //         return response(
  //           ResponseHeader::responseFailedWithData($sendData),
  //           $response
  //         );
  //       }
  //     } catch (\Throwable $th) {
  //       $response = 400;

  //       $sendData = [
  //         $response,
  //         'Harap Masukan Data Yang Valid',
  //         $th->getMessage()
  //       ];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @param int $id
  //    * @return JSON $response
  //    */
  //   public function detail(int $id)
  //   {
  //     try {
  //       $data = Member::find($id);
  //       if ($data && !empty($data)) {
  //         $response = 200;

  //         $sendData = [$response, 'Sukses', $data];
  //         return response(ResponseHeader::responseSuccess($sendData), $response);
  //       } elseif (!$data) {
  //         $msg = "Data tidak ditemukan";
  //         $code = 404;
  //         throw new ResponseException($msg, $code);
  //       } else {
  //         $msg = "Kesalahan Pada Server";
  //         $code = 500;
  //         throw new ResponseException($msg, $code);
  //       }
  //     } catch (ResponseException $th) {
  //       $response = $th->getCode();

  //       $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @param int $id
  //    * @param Request $request
  //    * @return JSON $response
  //    */
  //   public function update(int $id, Request $request)
  //   {
  //     try {
  //       $this->validate($request, [
  //         'code' => 'required',
  //         'name' => 'required'
  //       ]);
  //     } catch (\Throwable $th) {
  //       $response = 400;

  //       $sendData = [
  //         $response,
  //         'Gagal Validasi atau Data Yang Anda Cari Tidak Ada',
  //         $th->getMessage()
  //       ];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }

  //     try {
  //       $memberType = Member::find($id);
  //       $memberType->name = strtolower($request->input('name'));
  //       $memberType->limit_loan = $request->input('limit_loan');
  //       $memberType->loan_periode = $request->input('loan_periode');
  //       $memberType->membership_periode = $request->input('membership_periode');
  //       $memberType->fines = $request->input('fines');
  //       $memberType->save();

  //       $response = 200;

  //       $sendData = [$response, 'Berhasil Diubah', $memberType];
  //       return response(ResponseHeader::responseSuccess($sendData), $response);
  //     } catch (\Throwable $th) {
  //       $response = ResponseHeader::responseStatusFailed($th->getCode());

  //       $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @param Int $id
  //    * @return JSON $json
  //    */
  //   public function destroy(int $id)
  //   {
  //     try {
  //       $memberType = Member::find($id);
  //       $memberType->delete();

  //       $response = 200;
  //       $data = [
  //         'id' => $id
  //       ];

  //       $sendData = [$response, 'Berhasil Dihapus', $data];
  //       return response(ResponseHeader::responseSuccess($sendData), $response);
  //     } catch (\Throwable $th) {
  //       $response = ResponseHeader::responseStatusFailed($th->getCode());

  //       $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @param Request
  //    * @return JSON response
  //    */
  //   public function updateSome(Request $request)
  //   {
  //     try {
  //       $this->validate($request, [
  //         'update' => 'required'
  //       ]);

  //       try {
  //         $data = $request->input("update");
  //         if ($data && count($data) > 0) {
  //           foreach ($data as $key => $value) {
  //             $result = $data[$key];
  //             $memberType = Member::find($key);
  //             $memberType->name = strtolower($result['name']);
  //             $memberType->limit_loan = $result['limit_loan'];
  //             $memberType->loan_periode = $result['loan_periode'];
  //             $memberType->membership_periode = $result['membership_periode'];
  //             $memberType->fines = $result['fines'];
  //             $memberType->save();
  //           }

  //           $response = 200;

  //           $sendData = [
  //             $response,
  //             'Berhasil Diupdate',
  //             $request->input('update')
  //           ];
  //           return response(
  //             ResponseHeader::responseSuccess($sendData),
  //             $response
  //           );
  //         } elseif (count($data) < 0) {
  //           $msg = "Data tidak ditemukan";
  //           $code = 404;
  //           throw new ResponseException($msg, $code);
  //         } else {
  //           $msg = "Kesalahan Pada Server";
  //           $code = 500;
  //           throw new ResponseException($msg, $code);
  //         }
  //       } catch (ResponseException $th) {
  //         $message = $th->getCode();
  //         $response = [
  //           'time' => time(),
  //           'status' => $message,
  //           'message' => 'Gagal',
  //           'exception' => $th->getMessage()
  //         ];

  //         return response($response, $message);
  //       }
  //     } catch (\Throwable $th) {
  //       $response = 400;

  //       $sendData = [
  //         $response,
  //         'Harap Masukan Data Yang Valid',
  //         $th->getMessage()
  //       ];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @param Request $request
  //    * @return JSON response
  //    */
  //   public function destroySome(Request $request)
  //   {
  //     try {
  //       $this->validate($request, [
  //         'delete' => 'required'
  //       ]);

  //       try {
  //         $data = $request->input('delete');
  //         if ($data && count($data) > 0) {
  //           foreach ($data as $id) {
  //             $memberType = Member::find($id);
  //             $memberType->delete();
  //           }

  //           $response = 200;
  //           $dataResult = [
  //             'id' => $data
  //           ];

  //           $sendData = [$response, 'Berhasil Dihapus', $dataResult];
  //           return response(
  //             ResponseHeader::responseSuccess($sendData),
  //             $response
  //           );
  //         } elseif (count($data) < 0) {
  //           $msg = "Data tidak ditemukan";
  //           $code = 404;
  //           throw new ResponseException($msg, $code);
  //         } else {
  //           $msg = "Kesalahan Pada Server";
  //           $code = 500;
  //           throw new ResponseException($msg, $code);
  //         }
  //       } catch (ResponseException $th) {
  //         $message = $th->getCode();
  //         $response = [
  //           'time' => time(),
  //           'status' => $message,
  //           'message' => 'Gagal',
  //           'exception' => $th->getMessage()
  //         ];

  //         return response($response, $message);
  //       }
  //     } catch (\Throwable $th) {
  //       $response = 400;

  //       $sendData = [
  //         $response,
  //         'Harap Masukan Data Yang Valid',
  //         $th->getMessage()
  //       ];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @return JSON response response
  //    */
  //   public function retrieveDeleteHistoryData()
  //   {
  //     try {
  //       $data = Member::onlyTrashed()->get();

  //       $response = 200;

  //       $sendData = [$response, 'Sukses', $data];
  //       return response(ResponseHeader::responseSuccess($sendData), $response);
  //     } catch (\Throwable $th) {
  //       $response = ResponseHeader::responseStatusFailed($th->getCode());

  //       $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @param int $id
  //    * @return JSON response response
  //    */
  //   public function returnDeleteHistoryData(int $id)
  //   {
  //     try {
  //       $check = Member::find($id);
  //       $checkDataInSoftDelete = Member::onlyTrashed()
  //         ->where('id', $id)
  //         ->get();
  //       if (is_null($check) && count($checkDataInSoftDelete) < 1) {
  //         $msg = "Tipe member dengan nama: {$check->name} Tidak Dapat Ditemukan";
  //         $code = 400;
  //         throw new ResponseException($msg, $code);
  //       }

  //       Member::withTrashed()
  //         ->where('id', $id)
  //         ->restore();

  //       $data = Member::find($id);

  //       $response = 200;

  //       $sendData = [$response, 'Berhasil Dikembalikan', $data];
  //       return response(ResponseHeader::responseSuccess($sendData), $response);
  //     } catch (\Throwable $th) {
  //       $response = ResponseHeader::responseStatusFailed($th->getCode());

  //       $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @return JSON response response
  //    */
  //   public function returnAllDeleteHistoryData()
  //   {
  //     try {
  //       Member::onlyTrashed()->restore();

  //       $response = 200;

  //       $sendData = [$response, 'Berhasil Mengembalikan Semua'];
  //       return response(
  //         ResponseHeader::responseSuccessWithoutData($sendData),
  //         $response
  //       );
  //     } catch (\Throwable $th) {
  //       $response = ResponseHeader::responseStatusFailed($th->getCode());

  //       $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @param string $id
  //    * @return JSON response response
  //    */
  //   public function deleteHistoryData(int $id)
  //   {
  //     try {
  //       Member::withTrashed()
  //         ->where('id', $id)
  //         ->forceDelete();

  //       $response = 200;

  //       $sendData = [$response, 'Sukses Dihapus'];
  //       return response(
  //         ResponseHeader::responseSuccessWithoutData($sendData),
  //         $response
  //       );
  //     } catch (\Throwable $th) {
  //       $response = ResponseHeader::responseStatusFailed($th->getCode());

  //       $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @return JSON response response
  //    */
  //   public function deleteAllHistoryData()
  //   {
  //     try {
  //       Member::onlyTrashed()->forceDelete();

  //       $response = 200;

  //       $sendData = [$response, 'Sukses Dihapus'];
  //       return response(
  //         ResponseHeader::responseSuccessWithoutData($sendData),
  //         $response
  //       );
  //     } catch (\Throwable $th) {
  //       $response = ResponseHeader::responseStatusFailed($th->getCode());

  //       $sendData = [$response, 'Gagal Diproses', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }

  //   /**
  //    * @return JSON response
  //    */
  //   public function destroyAll()
  //   {
  //     try {
  //       Member::truncate();
  //       $response = 200;

  //       $sendData = [$response, 'Berhasil Menghapus Semua Data'];
  //       return response(
  //         ResponseHeader::responseSuccessWithoutData($sendData),
  //         $response
  //       );
  //     } catch (\Throwable $th) {
  //       $response = ResponseHeader::responseStatusFailed($th->getCode());

  //       $sendData = [$response, 'Gagal Menghapus Semua Data', $th->getMessage()];
  //       return response(ResponseHeader::responseFailed($sendData), $response);
  //     }
  //   }
}
