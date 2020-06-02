<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Place extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "place";
  protected $guarded = [];

  public function book_transaction()
  {
    return $this->hasMany("App\BookTransaction", "place_id", "id");
  }

  public function getNameAttribute($val)
  {
    return ucfirst($val);
  }

  public function getUpdatedAtAttribute($time)
  {
    return Carbon::create($time)->diffForHumans();
  }
}
