<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "place";
  protected $guarded = [];

  public function book()
  {
    return $this->hasMany("App\Book", "place_id", "id");
  }
}
