<?php

/**
 * Melakukan test untuk GMD.
 */

// use Faker\Generator as Faker;

class GmdTest extends TestCase
{
  /**
   * Testing untuk menyimpan data kedalam database GMD.
   */
  // public function testStoreGmd()
  // {
  //   $faker = Faker\Factory::create();
  //   $response = $this->call('POST', '/gmd', [
  //     'gmd_code' => $faker->randomNumber(3, false),
  //     'gmd_name' => $faker->name
  //   ]);

  //   $this->assertEquals(200, $response->status());
  // }

  public function testGetDataGmd()
  {
    $response = $this->call('GET', '/gmd');
    $this->assertEquals(200, $response->status());
  }
}
