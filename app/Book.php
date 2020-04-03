<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "book";
  protected $guarded = [];

  public function subject()
  {
    return $this->belongsToMany("App\Subject");
  }

  public function author()
  {
    return $this->belongsToMany("App\Author");
  }

  public function publisher()
  {
    return $this->belongsToMany("App\Publisher");
  }

  public function language()
  {
    return $this->belongsToMany("App\Language");
  }

  public function place()
  {
    return $this->belongsTo("App\Place");
  }
}
