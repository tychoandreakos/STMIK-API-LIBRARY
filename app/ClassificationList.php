<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassificationName extends Model
{
  protected $table = "classification_list";
  protected $guarded = [];

  public function classificationName()
  {
    return $this->belongsTo("App\ClassificationName");
  }
}
