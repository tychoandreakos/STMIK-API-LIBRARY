<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "publisher";
  protected $guarded = [];
}
