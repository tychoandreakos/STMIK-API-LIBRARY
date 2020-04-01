<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
  use SoftDeletes;

  protected $table = 'member';
  public $incrementing = false;
  protected $guarded = [];
  protected $with = ['memberType'];

  public function memberType()
  {
    return $this->hasOne('App\MemberType', 'id', 'membertype_id');
  }
}