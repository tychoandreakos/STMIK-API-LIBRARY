<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "subject";
  protected $guarded = [];

  public function book()
  {
    return $this->hasMany("App\Book", "subject_id", "id");
  }
}
