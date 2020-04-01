<?php

use App\MemberType;

class MemberTypeTest extends TestCase
{
  /**
   * @return void
   */
  public function testIndexCase()
  {
    $response = $this->call('GET', 'member-type');
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testStoreCase()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'member-type', [
      'name' => $faker->name,
      'limit_loan' => $faker->randomNumber(3, false),
      'loan_periode' => $faker->randomNumber(3, false),
      'membership_periode' => $faker->randomNumber(3, false),
      'fines' => $faker->randomFloat()
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedValidationStoreCase()
  {
    $response = $this->call('POST', 'member-type');
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDetailCase()
  {
    $id = MemberType::first()->id;
    $response = $this->call('GET', "member-type/${id}/detail");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDetailCase()
  {
    $id = 100;
    $response = $this->call('GET', "member-type/${id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * @return void
   */

  public function testSearchCase()
  {
    $member = MemberType::first();
    $name = $member->name;
    $response = $this->call("POST", "member-type/search", [
      "search" => $name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   *
   * @return void
   */
  public function testFailedSearchCase()
  {
    $search = "tidak ditemukan";
    $response = $this->call("POST", "member-type/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }
}
