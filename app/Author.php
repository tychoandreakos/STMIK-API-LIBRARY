<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
  use Models\Concerns\UsesUuid;
  use SoftDeletes;

  protected $table = "author";
  protected $guarded = [];
}
