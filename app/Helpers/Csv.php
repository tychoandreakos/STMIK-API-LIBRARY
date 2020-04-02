<?php

namespace App\Helpers;

class CSV
{
  public static function getPath()
  {
    $slash = "";
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      $slash = "\"";
    } else {
      $slash = "/";
    }

    $x = explode($slash, $_SERVER['DOCUMENT_ROOT']);
    array_pop($x);
    return join($slash, $x);
  }

  private static function popCsv($data)
  {
    $cut = 3;
    for ($i = 0; $i < $cut; $i++) {
      array_pop($data);
    }

    return $data;
  }

  public static function structuredCsv($csv)
  {
    $x = [];
    foreach ($csv as $record) {
      $x[] = $record;
    }

    $new2 = [];
    for ($i = 0; $i < count($x); $i++) {
      $new = [];
      foreach ($x[$i] as $r => $val) {
        if ($r == 9) {
          $new[] = $new[0];
        }
        $new[] = $val;
      }
      $new2[] = Csv::popCsv($new);
    }
    return $new2;
  }
}
