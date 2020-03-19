<?php

namespace App\Helpers;

class ResponseHeader
{
  /**
   * Fungsi ini merupakan fungsi untuk mempersingkat return status pada json.
   * urutan yang benar pada responseHeader adalah ['status', 'message' , 'data']
   *
   * @param Array $responseMessage
   * @return Array $messageHeader
   */
  public static function responseSuccess($responseMessage)
  {
    $responseMessage = self::addingToFirstArray($responseMessage, time());
    $response = [
      "responseKey" => ['time', 'status', 'message', 'data'],
      "responseResult" => $responseMessage
    ];

    $messageHeader = self::partialResponse($response);

    return $messageHeader;
  }

  /**
   * Fungsi ini merupakan fungsi untuk mempersingkat return status pada json.
   * urutan yang benar pada responseHeader adalah ['status', 'message' , 'exception']
   *
   * @param Array $responseMessage
   * @return Array $messageHeader
   */
  public static function responseSuccessWithoutData($responseMessage)
  {
    $responseMessage = self::addingToFirstArray($responseMessage, time());
    $response = [
      "responseKey" => ['time', 'status', 'message'],
      "responseResult" => $responseMessage
    ];

    $messageHeader = self::partialResponse($response);

    return $messageHeader;
  }

  /**
   * Fungsi ini merupakan fungsi untuk mempersingkat return status pada json.
   * urutan yang benar pada responseHeader adalah ['status', 'message' , 'data', 'exception]
   *
   * @param Array $responseMessage
   * @return Array $messageHeader
   */
  public static function responseFailedWithData($responseMessage)
  {
    $responseMessage = self::addingToFirstArray($responseMessage, time());

    $response = [
      "responseKey" => ['time', 'status', 'message', 'data', 'exception'],
      "responseResult" => $responseMessage
    ];

    $messageHeader = self::partialResponse($response);

    return $messageHeader;
  }

  /**
   * Fungsi ini merupakan fungsi untuk mempersingkat return status pada json.
   * urutan yang benar pada responseHeader adalah ['status', 'message' , 'data']
   *
   * @param Array $responseMessage
   * @return Array $messageHeader
   */
  public static function responseFailed($responseMessage)
  {
    $responseMessage = self::addingToFirstArray($responseMessage, time());

    $response = [
      "responseKey" => ['time', 'status', 'message', 'exception'],
      "responseResult" => $responseMessage
    ];

    $messageHeader = self::partialResponse($response);

    return $messageHeader;
  }

  private static function addingToFirstArray($arr, ...$var)
  {
    foreach ($var as $value) {
      array_unshift($arr, $value);
    }
    return $arr;
  }

  private static function partialResponse($response)
  {
    return array_combine($response['responseKey'], $response['responseResult']);
  }
}
