<?php

use App\Location;

class LocationTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil LOCATION.
   *
   *  @return void
   */
  public function testGetDataLocation()
  {
    $response = $this->call('GET', 'location');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database LOCATION.
   *
   *  @return void
   */
  public function testStoreLocation()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'location', [
      'code' => $faker->randomNumber(3, false),
      'name' => $faker->name
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database LOCATION.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStoreLocation()
  {
    $response = $this->call('POST', 'location');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update LOCATION.
   *
   *  @return void
   */
  public function testUpdateLocation()
  {
    $faker = Faker\Factory::create();
    $location = Location::first();
    $id = $location->id;
    $response = $this->call('PUT', "location/{$id}/edit", [
      'code' => $faker->randomNumber(3, false),
      'name' => $faker->name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update LOCATION.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdateLocation()
  {
    $location = Location::first();
    $id = $location->id;
    $response = $this->call('PUT', "location/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail LOCATION.
   *
   * @return void
   */
  public function testGetDetailLocation()
  {
    $location = Location::first();
    $id = $location->id;
    $response = $this->call('GET', "location/{$id}/detail");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing gagal ketika melakukan request terdapat detaul PUBLISHER
   * Testing ini bertujuan untuk melakukan test ketika data
   * yang dimasukan tidak tersedia didalam database.
   * Program akan memunculkan pesan 404.
   *
   * @return void
   */
  public function testFailedGetDetailLocation()
  {
    $id = '12345';
    $response = $this->call('GET', "location/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data PUBLISHER
   *
   * @return void
   */
  public function testSearchLocation()
  {
    $location = Location::first();
    $name = $location->name;
    $response = $this->call("POST", "location/search", [
      "search" => $name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Tesing ketika gagal mencari data PUBLISHER
   * Program akan memunculkan pesan 404 ketika
   * data yang diinginkan tidak tersedia
   *
   * @return void
   */
  public function testFailedSearchLocation()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "location/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data LOCATION.
   *
   * @return void
   */
  public function testDestroyLocation()
  {
    $location = Location::first();
    $id = $location->id;
    $response = $this->call("DELETE", "location/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data LOCATION.
   *
   * @return void
   */
  public function testFailedDestroyLocation()
  {
    $id = 123;
    $response = $this->call("DELETE", "location/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSomeLocation()
  {
    $faker = Faker\Factory::create();
    $location = Location::all();

    $response = $this->call("POST", "location/update", [
      "update" => [
        $location[0]->id => [
          "code" => $faker->randomNumber(3, false),
          "name" => $faker->name
        ],
        $location[1]->id => [
          "code" => $faker->randomNumber(3, false),
          "name" => $faker->name
        ]
      ]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal update lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedUpdateSomeLocation()
  {
    $response = $this->call("POST", "location/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySameLocation()
  {
    $location = Location::all();
    $response = $this->call("POST", "location/delete", [
      "delete" => [$location[0]->id, $location[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySameLocation()
  {
    $response = $this->call("POST", "location/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryDataLocation()
  {
    $response = $this->call("GET", "location/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistoryLocation()
  {
    $location = Location::onlyTrashed()->get();
    $response = $this->call("PUT", "location/{$location[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryDataLocation()
  {
    $location = Location::onlyTrashed()->get();
    $response = $this->call("DELETE", "location/{$location[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataLocation()
  {
    $response = $this->call("PUT", "location/restore");
    $this->assertEquals(200, $response->status());
  }
}
