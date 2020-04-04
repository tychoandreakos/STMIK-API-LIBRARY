<?php

use App\Biblio;
use App\BookTransaction;
use App\ClassificationList;
use App\Gmd;
use App\ItemStatus;
use App\Koleksi;
use App\Location;
use App\Pattern;

class BiblioTest extends TestCase
{
  /**
   * @return void
   */
  public function testIndexCase()
  {
    $response = $this->call('GET', 'bibliobigrafi');
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testStoreCase()
  {
    $faker = Faker\Factory::create();
    $pattern = Pattern::first()->id;
    $classification = ClassificationList::first()->id;
    $location = Location::first()->id;
    $gmd = Gmd::first()->id;
    $koleksi = Koleksi::first()->id;
    $book = BookTransaction::first()->id;
    $itemStatus = ItemStatus::first()->id;
    $response = $this->call('POST', 'bibliobigrafi', [
      "pattern_id" => $faker->word,
      "id_book_transaction" => $book,
      "id_pattern" => $pattern,
      "id_classification" => $classification,
      "id_location" => $location,
      "id_gmd" => $gmd,
      "id_koleksi" => $koleksi,
      "id_item_status" => $itemStatus
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedValidationStoreCase()
  {
    $response = $this->call('POST', 'bibliobigrafi');
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDetailCase()
  {
    $id = Biblio::first()->id;
    $response = $this->call('GET', "bibliobigrafi/${id}/detail");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDetailCase()
  {
    $id = 100;
    $response = $this->call('GET', "bibliobigrafi/${id}/detail");
    $this->assertEquals(404, $response->status());
  }

  public function testSearchRelationshipCase()
  {
    $gmd = Gmd::first();
    $name = $gmd->gmd_name;
    $response = $this->call("POST", "bibliobigrafi/search", [
      "search" => $name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroyCase()
  {
    $biblio = Biblio::first();
    $id = $biblio->id;
    $response = $this->call("DELETE", "bibliobigrafi/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroyCase()
  {
    $id = 123;
    $response = $this->call("DELETE", "bibliobigrafi/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * @return void
   */
  public function testUpdateSomeCase()
  {
    $faker = Faker\Factory::create();
    $biblio = Biblio::all();
    $pattern = Pattern::first()->id;
    $classification = ClassificationList::first()->id;
    $location = Location::first()->id;
    $gmd = Gmd::first()->id;
    $koleksi = Koleksi::first()->id;
    $itemStatus = ItemStatus::first()->id;
    $book = BookTransaction::first()->id;

    $response = $this->call("POST", "bibliobigrafi/update", [
      "update" => [
        $biblio[0]->id => [
          "pattern_id" => $faker->word,
          "id_book_transaction" => $book,
          "id_pattern" => $pattern,
          "id_classification" => $classification,
          "id_location" => $location,
          "id_gmd" => $gmd,
          "id_koleksi" => $koleksi,
          "id_item_status" => $itemStatus
        ],
        $biblio[1]->id => [
          "pattern_id" => $faker->word,
          "id_book_transaction" => $book,
          "id_pattern" => $pattern,
          "id_classification" => $classification,
          "id_location" => $location,
          "id_gmd" => $gmd,
          "id_koleksi" => $koleksi,
          "id_item_status" => $itemStatus
        ]
      ]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedUpdateSomeCase()
  {
    $response = $this->call("POST", "bibliobigrafi/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroySameCase()
  {
    $book = Biblio::all();
    $response = $this->call("POST", "bibliobigrafi/delete", [
      "delete" => [$book[0]->id, $book[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroySameCase()
  {
    $response = $this->call("POST", "bibliobigrafi/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testRetrieveDeleteHistoryDataCase()
  {
    $response = $this->call("GET", "bibliobigrafi/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testReturnDeleteHistoryDataCase()
  {
    $book = Biblio::onlyTrashed()->get();
    $response = $this->call("PUT", "bibliobigrafi/{$book[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testDeleteHistoryDataCase()
  {
    $book = Biblio::onlyTrashed()->get();
    $response = $this->call("DELETE", "bibliobigrafi/{$book[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataCase()
  {
    $response = $this->call("PUT", "bibliobigrafi/restore");
    $this->assertEquals(200, $response->status());
  }
}
