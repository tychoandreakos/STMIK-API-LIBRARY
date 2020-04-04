<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookTransaction extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "book_transaction";
  protected $guarded = [];

  protected $with = ["book","subject", "author", "publisher", "language", "place"];

  public function subject()
  {
    return $this->belongsTo("App\Subject", "id_subject", "id");
  }

  public function book()
  {
    return $this->belongsTo("App\Book", "id_book", "id");
  }

  public function author()
  {
    return $this->belongsTo("App\Author", "id_author", "id");
  }

  public function publisher()
  {
    return $this->belongsTo("App\Publisher", "id_publisher", "id");
  }

  public function language()
  {
    return $this->belongsTo("App\Bahasa", "id_language", "id");
  }

  public function place()
  {
    return $this->belongsTo("App\Place", "id_place", "id");
  }
}
