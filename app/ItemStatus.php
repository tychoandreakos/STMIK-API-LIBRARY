<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemStatus extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "item_status";
  protected $guarded = [];
}
