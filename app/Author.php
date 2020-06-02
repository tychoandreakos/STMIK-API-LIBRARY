<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Author extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = 'author';
  protected $guarded = [];

  public function book_transaction()
  {
    return $this->hasMany('App\BookTransaction', 'id_author', 'id');
  }

  public function getNameAttribute($val)
  {
    return strtoupper($val);
  }

  public function getUpdatedAtAttribute($time)
  {
    return Carbon::create($time)->diffForHumans();
  }
}
