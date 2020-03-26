<?php

use App\Place;

class PlaceTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil AUTHOR.
   *
   *  @return void
   */
  public function testGetDataPlace()
  {
    $response = $this->call('GET', 'place');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database AUTHOR.
   *
   *  @return void
   */
  public function testStorePlace()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'place', [
      'name' => $faker->name
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database AUTHOR.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStorePlace()
  {
    $response = $this->call('POST', 'place');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update AUTHOR.
   *
   *  @return void
   */
  public function testUpdatePlace()
  {
    $faker = Faker\Factory::create();
    $publisher = Place::first();
    $id = $publisher->id;
    $response = $this->call('PUT', "place/{$id}/edit", [
      'name' => $faker->name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update AUTHOR.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdatePlace()
  {
    $publisher = Place::first();
    $id = $publisher->id;
    $response = $this->call('PUT', "place/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail AUTHOR.
   *
   * @return void
   */
  public function testGetDetailPlace()
  {
    $publisher = Place::first();
    $id = $publisher->id;
    $response = $this->call('GET', "place/{$id}/detail");
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
  public function testFailedGetDetailPlace()
  {
    $id = '12345';
    $response = $this->call('GET', "place/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data PUBLISHER
   *
   * @return void
   */
  public function testSearchPlace()
  {
    $publisher = Place::first();
    $name = $publisher->name;
    $response = $this->call("POST", "place/search", [
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
  public function testFailedSearchPlace()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "place/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data AUTHOR.
   *
   * @return void
   */
  public function testDestroyPlace()
  {
    $publisher = Place::first();
    $id = $publisher->id;
    $response = $this->call("DELETE", "place/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data AUTHOR.
   *
   * @return void
   */
  public function testFailedDestroyPlace()
  {
    $id = 123;
    $response = $this->call("DELETE", "place/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSomePlace()
  {
    $faker = Faker\Factory::create();
    $publisher = Place::all();

    $response = $this->call("POST", "place/update", [
      "update" => [
        $publisher[0]->id => [
          "name" => $faker->name
        ],
        $publisher[1]->id => [
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
  public function testFailedUpdateSomePlace()
  {
    $response = $this->call("POST", "place/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySamePlace()
  {
    $publisher = Place::all();
    $response = $this->call("POST", "place/delete", [
      "delete" => [$publisher[0]->id, $publisher[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySamePlace()
  {
    $response = $this->call("POST", "place/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryDataPlace()
  {
    $response = $this->call("GET", "place/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistoryPlace()
  {
    $publisher = Place::onlyTrashed()->get();
    $response = $this->call("PUT", "place/{$publisher[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryDataPlace()
  {
    $publisher = Place::onlyTrashed()->get();
    $response = $this->call("DELETE", "place/{$publisher[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataPlace()
  {
    $response = $this->call("PUT", "place/restore");
    $this->assertEquals(200, $response->status());
  }
}
