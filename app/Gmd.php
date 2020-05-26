<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Gmd extends Model
{
  use Models\Concerns\UsesUuid; // Memanggil trait dan menggunakan Uuid;
  use SoftDeletes; // Menggunakan SoftDelete

  protected $table = "gmd";
  protected $guarded = [];

  public function biblio()
  {
    return $this->hasMany(Biblio::class, "id_gmd", "id");
  }

  public function getGmdCodeAttribute($val)
  {
    return strtoupper($val);
  }

  public function getGmdNameAttribute($val)
  {
    return ucfirst($val);
  }

  public function getUpdatedAtAttribute($time)
  {
    // Carbon::macro('fromTimestamp', static function (int $time) {
    //   return (new Carbon())->setTimestamp($time);
    // });
    // return Carbon::fromTimestamp(time())->format('Y-m-d H:i:s');

    return Carbon::create($time)
      ->diffForHumans();
  }
}
