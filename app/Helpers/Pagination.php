<?php

namespace App\Helpers;

class Pagination
{
  /**
   * Berfungsi untuk pagination.
   * Misalkan $request->skip ($input) === 1,
   * maka akan dikali 2 menadi 1 * 2 = 2;
   * 2 data akan diskip.
   *
   * @param integer|null $skip
   * @return integer|null $skip
   */
  public static function skip(?int $skip): ?int
  {
    $checkNull = self::checkNull($skip);
    return $checkNull ? 0 : $skip;
  }

  /**
   * Fungsi ini berfungsi untuk melakukan check apakah parameter
   * yang diberikan bertipe data null atau integer.
   *
   * @param integer|null $item
   * @return bool is_null($item) ? true : false;
   */
  public static function checkNull(?int $item): bool
  {
    return is_null($item);
  }

  /**
   * Berfungsi untuk pagination.
   *
   * @param integer|null $take
   * @return integer|null $take
   */
  public static function take(?int $take): ?int
  {
    $checkNull = self::checkNull($take);
    return $checkNull ? 5 : $take;
  }
}
