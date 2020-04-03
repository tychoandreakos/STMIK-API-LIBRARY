<?php

use App\Pattern;

class PatternTest extends TestCase
{
  /**
   * @return void
   */
  public function testIndexCase()
  {
    $response = $this->call('GET', 'pattern');
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testStoreCase()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'pattern', [
      'suffix' => $faker->word,
      'prefix' => $faker->word,
      'middle' => $faker->word,
      'last_pattern' => $faker->word
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedValidationStoreCase()
  {
    $response = $this->call('POST', 'pattern');
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */

  public function testSearchCase()
  {
    $pattern = Pattern::first();
    $last_pattern = $pattern->last_pattern;
    $response = $this->call("POST", "pattern/search", [
      "search" => $last_pattern
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedSearchCase()
  {
    $search = "tidak ditemukan";
    $response = $this->call("POST", "pattern/search", [
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
    $pattern = Pattern::first()->id;

    $response = $this->call("PUT", "pattern/${pattern}/edit", [
      'suffix' => $faker->word,
      'prefix' => $faker->word,
      'middle' => $faker->word,
      'last_pattern' => $faker->word
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedUpdateSomeCase()
  {
    $response = $this->call("PUT", "pattern/100/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroyCase()
  {
    $pattern = Pattern::first();
    $id = $pattern->id;
    $response = $this->call("DELETE", "pattern/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroyCase()
  {
    $id = 123;
    $response = $this->call("DELETE", "pattern/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }
}
