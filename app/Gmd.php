<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gmd extends Model
{
  use Models\Concerns\UsesUuid; // Memanggil trait dan menggunakan Uuid;
  use SoftDeletes; // Menggunakan SoftDelete

  protected $table = "gmd";
  protected $guarded = [];
}
