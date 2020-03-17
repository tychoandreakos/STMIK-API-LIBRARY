<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

// sumber: https://dev.to/wilburpowery/easily-use-uuids-in-laravel-45be
/**
 *
 * class atau trait ini berfungsi untuk mendapatkan ID menggunakan methode UUID.
 */

trait UsesUuid
{
  protected static function bootUsesUuid()
  {
    static::creating(function ($model) {
      if (!$model->getKey()) {
        $model->{$model->getKeyname()} = (string) Str::uuid();
      }
    });
  }

  public function getIncrementing()
  {
    return false;
  }

  public function getKeyType()
  {
    return 'string';
  }
}
