<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Location extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = 'location';
  protected $guarded = [];

  public function biblio()
  {
    return $this->hasMany(Biblio::class, 'id_location', 'id');
  }

  public function getCodeAttribute($val)
  {
    return strtoupper($val);
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
