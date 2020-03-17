<?php

/**
 * Melakukan test untuk GMD.
 */

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
}
