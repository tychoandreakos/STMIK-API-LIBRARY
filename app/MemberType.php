<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberType extends Model
{
  use SoftDeletes;

  protected $table = 'member_type';
  protected $guarded = [];

  public function member()
  {
    return $this->hasMany('App\Member', 'membertype_id', 'id');
  }

  public function getNameAttribute($val)
  {
    return ucfirst($val);
  }

  public function getFinesAttribute($val)
  {
    return "Rp. $val";
  }

  public function getUpdatedAtAttribute($time)
  {
    return Carbon::create($time)->diffForHumans();
  }
}
