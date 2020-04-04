<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Biblio extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "biblio";
  protected $guarded = [];
  public $incrementing = false;
  protected $with = [
    "bookTransaction",
    "pattern",
    "classification",
    "location",
    "gmd",
    "koleksi",
    "itemStatus"
  ];

  public function bookTransaction()
  {
    return $this->belongsTo(
      BookTransaction::class,
      "id_book_transaction",
      "id"
    );
  }

  public function pattern()
  {
    return $this->belongsTo(Pattern::class, 'id_pattern', 'id');
  }

  public function classification()
  {
    return $this->belongsTo(
      ClassificationList::class,
      'id_classification',
      'id'
    );
  }

  public function location()
  {
    return $this->belongsTo(Location::class, 'id_location', 'id');
  }

  public function gmd()
  {
    return $this->belongsTo(Gmd::class, 'id_gmd', 'id');
  }

  public function koleksi()
  {
    return $this->belongsTo(Koleksi::class, 'id_koleksi', 'id');
  }

  public function itemStatus()
  {
    return $this->belongsTo(ItemStatus::class, 'id_item_status', 'id');
  }
}
