<?php

use App\Bahasa;

class BahasaTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil BAHASA.
   *
   *  @return void
   */
  public function testGetDataBahasa()
  {
    $response = $this->call('GET', 'bahasa');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database BAHASA.
   *
   *  @return void
   */
  public function testStoreBahasa()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'bahasa', [
      'name' => $faker->country
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database BAHASA.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStoreBahasa()
  {
    $response = $this->call('POST', 'bahasa');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update BAHASA.
   *
   *  @return void
   */
  public function testUpdateBahasa()
  {
    $faker = Faker\Factory::create();
    $bahasa = Bahasa::first();
    $id = $bahasa->id;
    $response = $this->call('PUT', "bahasa/{$id}/edit", [
      'name' => $faker->country
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update BAHASA.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdateBahasa()
  {
    $bahasa = Bahasa::first();
    $id = $bahasa->id;
    $response = $this->call('PUT', "bahasa/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail BAHASA.
   *
   * @return void
   */
  public function testGetDetailBahasa()
  {
    $bahasa = Bahasa::first();
    $id = $bahasa->id;
    $response = $this->call('GET', "bahasa/{$id}/detail");
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
  public function testFailedGetDetailBahasa()
  {
    $id = '12345';
    $response = $this->call('GET', "bahasa/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data PUBLISHER
   *
   * @return void
   */
  public function testSearchBahasa()
  {
    $bahasa = Bahasa::first();
    $name = $bahasa->name;
    $response = $this->call("POST", "bahasa/search", [
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
  public function testFailedSearchBahasa()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "bahasa/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data BAHASA.
   *
   * @return void
   */
  public function testDestroyBahasa()
  {
    $bahasa = Bahasa::first();
    $id = $bahasa->id;
    $response = $this->call("DELETE", "bahasa/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data BAHASA.
   *
   * @return void
   */
  public function testFailedDestroyBahasa()
  {
    $id = 123;
    $response = $this->call("DELETE", "bahasa/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSomeBahasa()
  {
    $faker = Faker\Factory::create();
    $bahasa = Bahasa::all();

    $response = $this->call("POST", "bahasa/update", [
      "update" => [
        $bahasa[0]->id => [
          "name" => $faker->country
        ],
        $bahasa[1]->id => [
          "name" => $faker->country
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
  public function testFailedUpdateSomeBahasa()
  {
    $response = $this->call("POST", "bahasa/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySameBahasa()
  {
    $bahasa = Bahasa::all();
    $response = $this->call("POST", "bahasa/delete", [
      "delete" => [$bahasa[0]->id, $bahasa[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySameBahasa()
  {
    $response = $this->call("POST", "bahasa/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryDataBahasa()
  {
    $response = $this->call("GET", "bahasa/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistoryBahasa()
  {
    $bahasa = Bahasa::onlyTrashed()->get();
    $response = $this->call("PUT", "bahasa/{$bahasa[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryDataBahasa()
  {
    $bahasa = Bahasa::onlyTrashed()->get();
    $response = $this->call("DELETE", "bahasa/{$bahasa[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataBahasa()
  {
    $response = $this->call("PUT", "bahasa/restore");
    $this->assertEquals(200, $response->status());
  }
}
