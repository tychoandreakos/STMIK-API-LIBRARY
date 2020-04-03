<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bahasa extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "bahasa";
  protected $guarded = [];

  public function book()
  {
    return $this->hasMany("App\Book", "language_id", "id");
  }
}
