<?php

/**
 * Melakukan test untuk GMD.
 */

use App\Gmd;

class GmdTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil GMD.
   *
   *  @return void
   */
  public function testGetDataGmd()
  {
    $response = $this->call('GET', 'gmd');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database GMD.
   *
   *  @return void
   */
  public function testStoreGmd()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'gmd', [
      'gmd_code' => $faker->randomNumber(3, false),
      'gmd_name' => $faker->name
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database GMD.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStoreGmd()
  {
    $response = $this->call('POST', 'gmd');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update GMD.
   *
   *  @return void
   */
  public function testUpdateGmd()
  {
    $faker = Faker\Factory::create();
    $gmd = Gmd::first();
    $id = $gmd->id;
    $response = $this->call('PUT', "gmd/{$id}/edit", [
      'gmd_code' => $faker->randomNumber(3, false),
      'gmd_name' => $faker->name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update GMD.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdateGmd()
  {
    $gmd = Gmd::first();
    $id = $gmd->id;
    $response = $this->call('PUT', "gmd/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail GMD.
   *
   * @return void
   */
  public function testGetDetailGmd()
  {
    $gmd = Gmd::first();
    $id = $gmd->id;
    $response = $this->call('GET', "gmd/{$id}/detail");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing gagal ketika melakukan request terdapat detaul GMD
   * Testing ini bertujuan untuk melakukan test ketika data
   * yang dimasukan tidak tersedia didalam database.
   * Program akan memunculkan pesan 404.
   *
   * @return void
   */
  public function testFailedGetDetailGmd()
  {
    $id = '12345';
    $response = $this->call('GET', "gmd/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data GMD
   *
   * @return void
   */
  public function testSearchGmd()
  {
    $gmd = Gmd::first();
    $name = $gmd->gmd_name;
    $response = $this->call("POST", "gmd/search", [
      "search" => $name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Tesing ketika gagal mencari data GMD
   * Program akan memunculkan pesan 404 ketika
   * data yang diinginkan tidak tersedia
   *
   * @return void
   */
  public function testFailedSearchGmd()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "gmd/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data GMD.
   *
   * @return void
   */
  public function testDestroyGmd()
  {
    $gmd = Gmd::first();
    $id = $gmd->id;
    $response = $this->call("DELETE", "gmd/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data GMD.
   *
   * @return void
   */
  public function testFailedDestroyGmd()
  {
    $id = 123;
    $response = $this->call("DELETE", "gmd/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSome()
  {
    $faker = Faker\Factory::create();
    $gmd = Gmd::all();

    $response = $this->call("POST", "gmd/update", [
      "update" => [
        $gmd[0]->id => [
          "gmd_code" => $faker->randomNumber(3, false),
          "gmd_name" => $faker->name
        ],
        $gmd[1]->id => [
          "gmd_code" => $faker->randomNumber(3, false),
          "gmd_name" => $faker->name
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
  public function testFailedUpdateSome()
  {
    $response = $this->call("POST", "gmd/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySame()
  {
    $gmd = Gmd::all();
    $response = $this->call("POST", "gmd/delete", [
      "delete" => [$gmd[0]->id, $gmd[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySame()
  {
    $response = $this->call("POST", "gmd/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryData()
  {
    $response = $this->call("GET", "gmd/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistoryData()
  {
    $gmd = Gmd::onlyTrashed()->get();
    $response = $this->call("PUT", "gmd/{$gmd[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryData()
  {
    $gmd = Gmd::onlyTrashed()->get();
    $response = $this->call("DELETE", "gmd/{$gmd[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryData()
  {
    $response = $this->call("PUT", "gmd/restore");
    $this->assertEquals(200, $response->status());
  }
}
