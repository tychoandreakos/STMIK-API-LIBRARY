<?php

namespace App\Http\Controllers\Member;

use App\Helpers\ResponseHeader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MemberType;

class MemberTypeController extends Controller
{
  public function index()
  {
  }

  /**
   * @param Request $request
   * @param MemberType $memberType
   * @return JSON $json
   */
  public function store(MemberType $memberType, Request $request)
  {
    try {
      $this->validate($request, [
        'name' => 'required|unique:member_type|max:50',
        'limit_loan' => 'required',
        'loan_periode' => 'required',
        'membership_periode' => 'required',
        'fines' => 'required'
      ]);

      try {
        $memberType->name = strtolower($request->name);
        $memberType->limit_loan = $request->limit_loan;
        $memberType->loan_periode = $request->loan_periode;
        $memberType->membership_periode = $request->membership_periode;
        $memberType->fines = $request->fines;

        $memberType->save();
        $response = 201;

        $sendData = [$response, 'Berhasil Disimpan'];

        return response(ResponseHeader::responseFailed($sendData, $response));
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

  public function detail()
  {
  }

  public function update()
  {
  }

  public function softDelete()
  {
  }

  public function restore()
  {
  }

  public function delete()
  {
  }
}
