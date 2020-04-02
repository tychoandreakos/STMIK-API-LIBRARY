<?php

use App\ClassificationList;

class ClassificationListTest extends TestCase
{
  /**
   * @return void
   */
  public function testIndexCase()
  {
    $response = $this->call('GET', 'classification');
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testStoreCase()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'classification', [
      'name' => $faker->name
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedValidationStoreCase()
  {
    $response = $this->call('POST', 'classification');
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */

  public function testSearchCase()
  {
    $classification = ClassificationList::first();
    $name = $classification->name;
    $response = $this->call("POST", "classification/search", [
      "search" => $name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedSearchCase()
  {
    $search = "tidak ditemukan";
    $response = $this->call("POST", "classification/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * @return void
   */
  public function testUpdateCase()
  {
    $faker = Faker\Factory::create();
    $classification = ClassificationList::first()->id;

    $response = $this->call(
      "PUT",
      "classification/${classification}/edit",
      [
        "name" => $faker->firstName()
      ]
    );
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedUpdateSomeCase()
  {
    $response = $this->call("PUT", "classification/100/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroyCase()
  {
    $classification = ClassificationList::first();
    $id = $classification->id;
    $response = $this->call("DELETE", "classification/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroyCase()
  {
    $id = 123;
    $response = $this->call("DELETE", "classification/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }
}
