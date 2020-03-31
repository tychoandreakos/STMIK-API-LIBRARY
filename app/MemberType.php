<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberType extends Model
{
  use SoftDeletes;

  protected $table = 'member_type';
  protected $guarded = [];
}
