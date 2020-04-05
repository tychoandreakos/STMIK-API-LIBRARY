<?php

use App\Member;
use App\MemberType;

class MemberTest extends TestCase
{
  /**
   * @return void
   */
  public function testIndexCase()
  {
    $response = $this->call('GET', 'member');
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testStoreCase()
  {
    $faker = Faker\Factory::create();
    $typeMemberID = MemberType::latest()->get()[0]->id;
    $response = $this->call('POST', 'member', [
      'id' => $faker->randomNumber(3, false),
      'membertype_id' => $typeMemberID,
      'name' => $faker->name,
      'birthdate' => $faker->date('Y/m/d', 'now'),
      'member_since' => $faker->date('Y/m/d', 'now'),
      'expiry_date' => $faker->date('Y/m/d', 'now'),
      'alamat' => $faker->address,
      'username' => $faker->username,
      'email' => $faker->email,
      'password' => "123",
      'phone' => $faker->phoneNumber,
      'pending' => 0,
      'image' =>
        "https://direct.rhapsody.com/imageserver/images/alb.222782138/70x70.jpeg"
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedValidationStoreCase()
  {
    $response = $this->call('POST', 'member');
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDetailCase()
  {
    $id = Member::first()->id;
    $response = $this->call('GET', "member/${id}/detail");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDetailCase()
  {
    $id = 100;
    $response = $this->call('GET', "member/${id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * @return void
   */

  public function testSearchCase()
  {
    $member = Member::first();
    $name = $member->name;
    $response = $this->call("POST", "member/search", [
      "search" => $name
    ]);
    $this->assertEquals(200, $response->status());
  }

  public function testSearchRelationshipCase()
  {
    $member = MemberType::first();
    $name = $member->name;
    $response = $this->call("POST", "member/search", [
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
    $response = $this->call("POST", "member/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroyCase()
  {
    $member = Member::first();
    $id = $member->id;
    $response = $this->call("DELETE", "member/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroyCase()
  {
    $id = 123;
    $response = $this->call("DELETE", "member/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * @return void
   */
  public function testUpdateSomeCase()
  {
    $faker = Faker\Factory::create();
    $member = Member::latest()->get();
    $typeMemberID = MemberType::latest()->get()[0]->id;

    $response = $this->call("POST", "member/update", [
      "update" => [
        $member[0]->id => [
          'membertype_id' => $typeMemberID,
          'name' => $faker->name,
          'birthdate' => $faker->date('Y/m/d', 'now'),
          'member_since' => $faker->date('Y/m/d', 'now'),
          'expiry_date' => $faker->date('Y/m/d', 'now'),
          'alamat' => $faker->address,
          'username' => $faker->username,
          'email' => $faker->email,
          'phone' => $faker->phoneNumber,
          'pending' => 0,
          'image' =>
            "https://direct.rhapsody.com/imageserver/images/alb.222782138/70x70.jpeg"
        ],
        $member[1]->id => [
          'membertype_id' => $typeMemberID,
          'name' => $faker->name,
          'birthdate' => $faker->date('Y/m/d', 'now'),
          'member_since' => $faker->date('Y/m/d', 'now'),
          'expiry_date' => $faker->date('Y/m/d', 'now'),
          'alamat' => $faker->address,
          'username' => $faker->username,
          'email' => $faker->email,
          'phone' => $faker->phoneNumber,
          'pending' => 0,
          'image' =>
            "https://direct.rhapsody.com/imageserver/images/alb.222782138/70x70.jpeg"
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
    $response = $this->call("POST", "member/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroySameCase()
  {
    $member = Member::latest()->get();
    $response = $this->call("POST", "member/delete", [
      "delete" => [$member[0]->id, $member[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroySameCase()
  {
    $response = $this->call("POST", "member/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testRetrieveDeleteHistoryDataCase()
  {
    $response = $this->call("GET", "member/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testReturnDeleteHistoryDataCase()
  {
    $member = Member::onlyTrashed()->get();
    $response = $this->call("PUT", "member/{$member[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testDeleteHistoryDataCase()
  {
    $member = Member::onlyTrashed()->get();
    $response = $this->call("DELETE", "member/{$member[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataCase()
  {
    $response = $this->call("PUT", "member/restore");
    $this->assertEquals(200, $response->status());
  }
}
