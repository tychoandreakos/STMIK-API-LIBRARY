<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
  use SoftDeletes;

  protected $table = 'member';
  public $incrementing = false;
  protected $guarded = [];
  protected $with = ['memberType'];
  protected $hidden = ['password'];

  public function memberType()
  {
    return $this->belongsTo('App\MemberType', 'membertype_id', 'id');
  }

  public function getEmailAttribute($val)
  {
    return ucfirst($val);
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
