<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gmd extends Model
{
  use Models\Concerns\UsesUuid;

  protected $guarded = [];
}
