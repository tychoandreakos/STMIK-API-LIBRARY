<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificationNameSeeder extends Seeder
{
  protected $data = ["main", "hundred", "thousand"];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    foreach ($this->data as $field) {
      DB::table('classification_name')->insert([
        'name' => $field
      ]);
    }
  }
}
