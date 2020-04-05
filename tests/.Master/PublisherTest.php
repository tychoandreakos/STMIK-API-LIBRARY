<?php

use App\Publisher;

class PublisherTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil PUBLISHER.
   *
   *  @return void
   */
  public function testGetDataPublisher()
  {
    $response = $this->call('GET', 'publisher');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database PUBLISHER.
   *
   *  @return void
   */
  public function testStorePublisher()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'publisher', [
      'name' => $faker->firstName()
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database PUBLISHER.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStorePublisher()
  {
    $response = $this->call('POST', 'publisher');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update PUBLISHER.
   *
   *  @return void
   */
  public function testUpdatePublisher()
  {
    $faker = Faker\Factory::create();
    $publisher = Publisher::first();
    $id = $publisher->id;
    $response = $this->call('PUT', "publisher/{$id}/edit", [
      'name' => $faker->firstName()
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update PUBLISHER.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdatePublisher()
  {
    $publisher = Publisher::first();
    $id = $publisher->id;
    $response = $this->call('PUT', "publisher/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail PUBLISHER.
   *
   * @return void
   */
  public function testGetDetailPublisher()
  {
    $publisher = Publisher::first();
    $id = $publisher->id;
    $response = $this->call('GET', "publisher/{$id}/detail");
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
  public function testFailedGetDetailPublisher()
  {
    $id = '12345';
    $response = $this->call('GET', "publisher/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data PUBLISHER
   *
   * @return void
   */
  public function testSearchPublisher()
  {
    $publisher = Publisher::first();
    $name = $publisher->name;
    $response = $this->call("POST", "publisher/search", [
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
  public function testFailedSearchPublisher()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "publisher/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data PUBLISHER.
   *
   * @return void
   */
  public function testDestroyPublisher()
  {
    $publisher = Publisher::first();
    $id = $publisher->id;
    $response = $this->call("DELETE", "publisher/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data PUBLISHER.
   *
   * @return void
   */
  public function testFailedDestroyPublisher()
  {
    $id = 123;
    $response = $this->call("DELETE", "publisher/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSomePublisher()
  {
    $faker = Faker\Factory::create();
    $publisher = Publisher::latest()->get();

    $response = $this->call("POST", "publisher/update", [
      "update" => [
        $publisher[0]->id => [
          "name" => $faker->firstName()
        ],
        $publisher[1]->id => [
          "name" => $faker->firstName()
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
  public function testFailedUpdateSomePublisher()
  {
    $response = $this->call("POST", "publisher/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySamePublisher()
  {
    $publisher = Publisher::latest()->get();
    $response = $this->call("POST", "publisher/delete", [
      "delete" => [$publisher[0]->id, $publisher[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySamePublisher()
  {
    $response = $this->call("POST", "publisher/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryDataPublisher()
  {
    $response = $this->call("GET", "publisher/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistoryDataPublisher()
  {
    $publisher = Publisher::onlyTrashed()->get();
    $response = $this->call("PUT", "publisher/{$publisher[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryDataPublisher()
  {
    $publisher = Publisher::onlyTrashed()->get();
    $response = $this->call("DELETE", "publisher/{$publisher[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataPublisher()
  {
    $response = $this->call("PUT", "publisher/restore");
    $this->assertEquals(200, $response->status());
  }
}
