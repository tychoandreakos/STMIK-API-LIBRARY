<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Koleksi extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "koleksi";
  protected $guarded = [];

  public function biblio()
  {
    return $this->hasMany(Biblio::class, "id_koleksi", "id");
  }
}
