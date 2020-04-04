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

  public function book_transaction()
  {
    return $this->hasMany("App\BookTransaction", "id_book", "id");
  }
}
