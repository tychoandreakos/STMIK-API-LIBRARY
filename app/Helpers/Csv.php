<?php

namespace App\Helpers;

use App\MemberType;
use League\Csv\Reader;
use League\Csv\Writer;
use SplTempFileObject;

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

  public static function writeCSV()
  {
    return Writer::createFromFileObject(new SplTempFileObject());
  }

  public static function senayanCSV($file)
  {
    return Csv::fixedSenayan($file);
  }

  private static function fixedSenayan($csv)
  {
    $x = Csv::iterateCsv($csv);
    $new2 = [];
    for ($i = 0; $i < count($x); $i++) {
      $new = [];
      $field = $x[$i];
      $newEle = [
        $field[0],
        MemberType::first()->id,
        $field[1],
        $field[16],
        $field[14],
        $field[15],
        $field[5],
        Csv::getUsername($field[4]),
        $field[4],
        $field[0],
        $field[11],
        0,
        $field[9]
      ];
      $new2[] = array_merge($new, $newEle);
    }

    return $new2;
  }

  private static function getUsername($email)
  {
    return explode("@", $email)[0];
  }

  private static function iterateCsv($csv)
  {
    $x = [];
    foreach ($csv as $record) {
      $x[] = $record;
    }

    return $x;
  }

  private static function structureArrayCsv($csv)
  {
    $new2 = [];
    for ($i = 0; $i < count($csv); $i++) {
      $new = [];
      foreach ($csv[$i] as $r => $val) {
        if ($r == 9) {
          $new[] = $new[0];
        }
        $new[] = $val;
      }
      $new2[] = Csv::popCsv($new);
    }

    return $new2;
  }

  private static function popCsv($data)
  {
    $cut = 3;
    for ($i = 0; $i < $cut; $i++) {
      array_pop($data);
    }

    return $data;
  }

  public static function getCsv($file)
  {
    return Reader::createFromPath(CSV::getPath() . "/storage/app/csv/{$file}");
  }

  public static function structuredCsv($csv)
  {
    $x = Csv::iterateCsv($csv);
    $new2 = Csv::structureArrayCsv($x);
    return $new2;
  }
}
