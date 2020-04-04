<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Pattern extends Model
{
  protected $table = "pattern";
  protected $guarded = [];

  public function biblio()
  {
    return $this->hasMany(Biblio::class, "id_pattern", "id");
  }
}
