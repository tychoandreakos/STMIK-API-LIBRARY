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
  public static function responseFailed(array $responseMessage): array
  {
    $responseMessage = self::addingToFirstArray($responseMessage, time());

    $response = [
      "responseKey" => ['time', 'status', 'message', 'exception'],
      "responseResult" => $responseMessage
    ];

    $messageHeader = self::partialResponse($response);

    return $messageHeader;
  }

  /**
   * Fungsi ini merupakan fungsi untuk mempersingkat return status pada json.
   * urutan yang benar pada responseHeader adalah ['status', 'message' , 'data']
   *
   * @param int $code
   * @return int $code
   */
  public static function responseStatusFailed(int $code): int
  {
    return $code ? $code : 500;
  }

  /**
   * Fungsi ini merupakan fungsi untuk menambahkan item baru terhadapt response.
   *
   * @param array arr
   * @param ?array $var
   * @return array arr
   */
  private static function addingToFirstArray(array $arr, ...$var): array
  {
    foreach ($var as $value) {
      array_unshift($arr, $value);
    }
    return $arr;
  }

  /**
   * Fungsi ini merupakan fungsi untuk menyatukan array.
   *
   * @param array $response
   * @return array $response
   */
  private static function partialResponse(array $response): array
  {
    return array_combine($response['responseKey'], $response['responseResult']);
  }
}
