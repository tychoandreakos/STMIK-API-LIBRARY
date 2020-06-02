<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Koleksi extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = 'koleksi';
  protected $guarded = [];

  public function biblio()
  {
    return $this->hasMany(Biblio::class, 'id_koleksi', 'id');
  }

  public function getTipeAttribute($val)
  {
    return ucfirst($val);
  }

  public function getUpdatedAtAttribute($time)
  {
    return Carbon::create($time)->diffForHumans();
  }
}
