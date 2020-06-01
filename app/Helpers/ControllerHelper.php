<?php

namespace App\Helpers;

class ControllerHelper
{
  function update($Model, $fillable, $result)
  {
    foreach ($fillable as $column) {
      if ($column != 'id') {
        $field = $result[$column];
        if (strpos($field, '/') > 0 || is_numeric($field)) {
          $Model->$column = $field;
        } else {
          $Model->$column = strtolower($field);
        }
      }
    }
    $Model->save();
  }
}
