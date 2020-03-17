<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gmd extends Model
{
  use Models\Concerns\UsesUuid; // Memanggil trait dan menggunakan Uuid;

  protected $table = "gmd";
  protected $guarded = [];
}
