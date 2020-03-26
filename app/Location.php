<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "location";
  protected $guarded = [];
}
