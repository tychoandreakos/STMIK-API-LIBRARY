<?php

use App\ItemStatus;

class ItemStatusTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil ITEM STATUS.
   *
   *  @return void
   */
  public function testGetDataItemStatus()
  {
    $response = $this->call('GET', 'item');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database ITEM STATUS.
   *
   *  @return void
   */
  public function testStoreItemStatus()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'item', [
      'code' => $faker->randomNumber(3, false),
      'name' => $faker->name
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database ITEM STATUS.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStoreItemStatus()
  {
    $response = $this->call('POST', 'item');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update ITEM STATUS.
   *
   *  @return void
   */
  public function testUpdateItemStatus()
  {
    $faker = Faker\Factory::create();
    $itemStatus = ItemStatus::first();
    $id = $itemStatus->id;
    $response = $this->call('PUT', "item/{$id}/edit", [
      'code' => $faker->randomNumber(3, false),
      'name' => $faker->name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update ITEM STATUS.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdateItemStatus()
  {
    $itemStatus = ItemStatus::first();
    $id = $itemStatus->id;
    $response = $this->call('PUT', "item/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail ITEM STATUS.
   *
   * @return void
   */
  public function testGetDetailItemStatus()
  {
    $itemStatus = ItemStatus::first();
    $id = $itemStatus->id;
    $response = $this->call('GET', "item/{$id}/detail");
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
  public function testFailedGetDetailItemStatus()
  {
    $id = '12345';
    $response = $this->call('GET', "item/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data PUBLISHER
   *
   * @return void
   */
  public function testSearchItemStatus()
  {
    $itemStatus = ItemStatus::first();
    $name = $itemStatus->name;
    $response = $this->call("POST", "item/search", [
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
  public function testFailedSearchItemStatus()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "item/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data ITEM STATUS.
   *
   * @return void
   */
  public function testDestroyItemStatus()
  {
    $itemStatus = ItemStatus::first();
    $id = $itemStatus->id;
    $response = $this->call("DELETE", "item/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data ITEM STATUS.
   *
   * @return void
   */
  public function testFailedDestroyItemStatus()
  {
    $id = 123;
    $response = $this->call("DELETE", "item/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSomeItemStatus()
  {
    $faker = Faker\Factory::create();
    $itemStatus = ItemStatus::latest()->get();

    $response = $this->call("POST", "item/update", [
      "update" => [
        $itemStatus[0]->id => [
          "code" => $faker->randomNumber(3, false),
          "name" => $faker->name
        ],
        $itemStatus[1]->id => [
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
  public function testFailedUpdateSomeItemStatus()
  {
    $response = $this->call("POST", "item/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySameItemStatus()
  {
    $itemStatus = ItemStatus::latest()->get();
    $response = $this->call("POST", "item/delete", [
      "delete" => [$itemStatus[0]->id, $itemStatus[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySameItemStatus()
  {
    $response = $this->call("POST", "item/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryDataItemStatus()
  {
    $response = $this->call("GET", "item/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistoryItemStatus()
  {
    $itemStatus = ItemStatus::onlyTrashed()->get();
    $response = $this->call("PUT", "item/{$itemStatus[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryDataItemStatus()
  {
    $itemStatus = ItemStatus::onlyTrashed()->get();
    $response = $this->call("DELETE", "item/{$itemStatus[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataItemStatus()
  {
    $response = $this->call("PUT", "item/restore");
    $this->assertEquals(200, $response->status());
  }
}
