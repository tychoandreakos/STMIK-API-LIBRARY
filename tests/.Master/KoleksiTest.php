<?php

use App\Koleksi;

class KoleksiTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil KOLEKSI.
   *
   *  @return void
   */
  public function testGetDataKoleksi()
  {
    $response = $this->call('GET', 'koleksi');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database KOLEKSI.
   *
   *  @return void
   */
  public function testStoreKoleksi()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'koleksi', [
      'tipe' => $faker->word
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database KOLEKSI.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStoreKoleksi()
  {
    $response = $this->call('POST', 'koleksi');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update KOLEKSI.
   *
   *  @return void
   */
  public function testUpdateKoleksi()
  {
    $faker = Faker\Factory::create();
    $koleksi = Koleksi::first();
    $id = $koleksi->id;
    $response = $this->call('PUT', "koleksi/{$id}/edit", [
      'tipe' => $faker->word
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update KOLEKSI.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdateKoleksi()
  {
    $koleksi = Koleksi::first();
    $id = $koleksi->id;
    $response = $this->call('PUT', "koleksi/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail KOLEKSI.
   *
   * @return void
   */
  public function testGetDetailKoleksi()
  {
    $koleksi = Koleksi::first();
    $id = $koleksi->id;
    $response = $this->call('GET', "koleksi/{$id}/detail");
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
  public function testFailedGetDetailKoleksi()
  {
    $id = '12345';
    $response = $this->call('GET', "koleksi/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data PUBLISHER
   *
   * @return void
   */
  public function testSearchKoleksi()
  {
    $koleksi = Koleksi::first();
    $tipe = $koleksi->tipe;
    $response = $this->call("POST", "koleksi/search", [
      "search" => $tipe
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
  public function testFailedSearchKoleksi()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "koleksi/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data KOLEKSI.
   *
   * @return void
   */
  public function testDestroyKoleksi()
  {
    $koleksi = Koleksi::first();
    $id = $koleksi->id;
    $response = $this->call("DELETE", "koleksi/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data KOLEKSI.
   *
   * @return void
   */
  public function testFailedDestroyKoleksi()
  {
    $id = 123;
    $response = $this->call("DELETE", "koleksi/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSomeKoleksi()
  {
    $faker = Faker\Factory::create();
    $koleksi = Koleksi::all();

    $response = $this->call("POST", "koleksi/update", [
      "update" => [
        $koleksi[0]->id => [
          "tipe" => $faker->word
        ],
        $koleksi[1]->id => [
          "tipe" => $faker->word
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
  public function testFailedUpdateSomeKoleksi()
  {
    $response = $this->call("POST", "koleksi/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySameKoleksi()
  {
    $koleksi = Koleksi::all();
    $response = $this->call("POST", "koleksi/delete", [
      "delete" => [$koleksi[0]->id, $koleksi[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySameKoleksi()
  {
    $response = $this->call("POST", "koleksi/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryDataKoleksi()
  {
    $response = $this->call("GET", "koleksi/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistoryKoleksi()
  {
    $koleksi = Koleksi::onlyTrashed()->get();
    $response = $this->call("PUT", "koleksi/{$koleksi[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryDataKoleksi()
  {
    $koleksi = Koleksi::onlyTrashed()->get();
    $response = $this->call("DELETE", "koleksi/{$koleksi[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataKoleksi()
  {
    $response = $this->call("PUT", "koleksi/restore");
    $this->assertEquals(200, $response->status());
  }
}
