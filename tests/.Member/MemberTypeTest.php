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

  /**
   * @return void
   */
  public function testDestroyCase()
  {
    $memberType = MemberType::first();
    $id = $memberType->id;
    $response = $this->call("DELETE", "member-type/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroyCase()
  {
    $id = 123;
    $response = $this->call("DELETE", "member-type/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * @return void
   */
  public function testUpdateSomeCase()
  {
    $faker = Faker\Factory::create();
    $memberType = MemberType::latest()->get();

    $response = $this->call("POST", "member-type/update", [
      "update" => [
        $memberType[0]->id => [
          "name" => $faker->firstName(),
          'limit_loan' => $faker->randomNumber(3, false),
          'loan_periode' => $faker->randomNumber(3, false),
          'membership_periode' => $faker->randomNumber(3, false),
          'fines' => $faker->randomFloat()
        ],
        $memberType[1]->id => [
          "name" => $faker->firstName(),
          'limit_loan' => $faker->randomNumber(3, false),
          'loan_periode' => $faker->randomNumber(3, false),
          'membership_periode' => $faker->randomNumber(3, false),
          'fines' => $faker->randomFloat()
        ]
      ]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedUpdateSomeCase()
  {
    $response = $this->call("POST", "member-type/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroySameCase()
  {
    $memberType = MemberType::latest()->get();
    $response = $this->call("POST", "member-type/delete", [
      "delete" => [$memberType[0]->id, $memberType[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroySameCase()
  {
    $response = $this->call("POST", "member-type/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testRetrieveDeleteHistoryDataCase()
  {
    $response = $this->call("GET", "member-type/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testReturnDeleteHistoryDataCase()
  {
    $memberType = MemberType::onlyTrashed()->get();
    $response = $this->call("PUT", "member-type/{$memberType[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testDeleteHistoryDataCase()
  {
    $memberType = MemberType::onlyTrashed()->get();
    $response = $this->call(
      "DELETE",
      "member-type/{$memberType[0]->id}/destroy"
    );
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataCase()
  {
    $response = $this->call("PUT", "member-type/restore");
    $this->assertEquals(200, $response->status());
  }
}
