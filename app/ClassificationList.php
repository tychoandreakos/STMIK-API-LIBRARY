<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassificationList extends Model
{
  protected $table = "classification_list";
  protected $guarded = [];
  public $incrementing = false;
  public $timestamp = false;

  public function classificationName()
  {
    return $this->belongsTo("App\ClassificationName");
  }
}
