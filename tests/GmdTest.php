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

    $this->assertEquals(200, $response->status());
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
  public function testUpdateGmd()
  {
    $id = "20";
    $response = $this->call('PUT', "gmd/{$id}");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data GMD.
   * 
   * @return void
   */
  public function testDeleteGmd()
  {
    $id = "20";
    $response = $this->call("DELETE", "gmd/{$id}");
    $this->assertEquals(200, $response->status());
  }
}
