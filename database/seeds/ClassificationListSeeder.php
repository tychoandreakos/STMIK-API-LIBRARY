<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificationListSeeder extends Seeder
{
  protected $mainId = ["001"];
  protected $mainName = ["asas"];

  protected $secondId = ["003"];
  protected $secondName = ["sdsds"];

  protected $thirdId = ["004"];
  protected $thirdName = ["weww"];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $main = $this->merge($this->mainId, $this->mainName);
    $this->saveMe($main, 1);

    $second = $this->merge($this->secondId, $this->secondName);
    $this->saveMe($second, 2);

    $third = $this->merge($this->thirdId, $this->thirdName);
    $this->saveMe($third, 3);
  }

  private function saveMe($field, $code)
  {
    DB::table('classification_list')->insert([
      'id' => $field[0],
      'name_id' => $code,
      'name' => $field[1]
    ]);
  }

  private function merge($arr1, $arr2)
  {
    return array_merge($arr1, $arr2);
  }
}
