<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassificationName extends Model
{
  protected $table = "classification_name";
  protected $guarded = [];
  public $timestamp = false;

  public function classificationList()
  {
    return $this->hasMany("App\ClassificationList", 'name_id', 'id');
  }
}
