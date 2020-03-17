<?php

/**
 * Melakukan test untuk GMD.
 */

use App\Gmd;

class GmdTest extends TestCase
{
  /**
   * Testing untuk menyimpan data kedalam database GMD.
   *
   *  @return void
   */
  public function testStoreGmd()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', '/gmd', [
      'gmd_code' => $faker->randomNumber(3, false),
      'gmd_name' => $faker->name
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk menampilkan hasil GMD.
   *
   *  @return void
   */
  public function testGetDataGmd()
  {
    $response = $this->call('GET', '/gmd');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika melakukan update GMD.
   *
   *  @return void
   */
  public function testErrorUpdateGmd()
  {
    $id = "20";
    $response = $this->call('PUT', "gmd/{$id}");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail GMD.
   *
   * @return void
   */

  public function testGetDetailGmd()
  {
    $id = "55ee297c-22d4-423f-ac04-faa8974463bb";
    $response = $this->call('GET', "gmd/detail/{$id}");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing gagal ketika melakukan request terhdapat detaul GMD
   *
   * @return void
   */
  public function testFailedGetDetailGmd()
  {
    $id = "20";
    $response = $this->call('GET', "gmd/detail/{$id}");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Tesing ketika mencari data GMD
   *
   * @return void
   */
  public function testSearchGmd()
  {
    $search = "Abner";
    $response = $this->call("POST", "gmd/search", [
      "search" => $search
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data GMD.
   *
   * @return void
   */
  public function testErrorDeleteGmd()
  {
    $id = "20";
    $response = $this->call("DELETE", "gmd/{$id}");
    $this->assertEquals(500, $response->status());
  }
}
