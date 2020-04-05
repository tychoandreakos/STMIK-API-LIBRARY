<?php

use App\Book;
use App\Author;
use App\Bahasa;
use App\BookTransaction;
use App\Place;
use App\Publisher;
use App\Subject;

class BookTransactionTest extends TestCase
{
  /**
   * @return void
   */
  public function testIndexCase()
  {
    $response = $this->call('GET', 'book-list');
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testStoreCase()
  {
    $faker = Faker\Factory::create();
    $author = Author::first()->id;
    $publisher = Publisher::first()->id;
    $language = Bahasa::first()->id;
    $place = Place::first()->id;
    $book = Book::first()->id;
    $subject = Subject::first()->id;
    $response = $this->call('POST', 'book-list', [
      "id_book" => $book,
      "id_author" => $author,
      "id_publisher" => $publisher,
      "id_language" => $language,
      "id_place" => $place,
      "id_subject" => $subject
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedValidationStoreCase()
  {
    $response = $this->call('POST', 'book-list');
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDetailCase()
  {
    $id = BookTransaction::first()->id;
    $response = $this->call('GET', "book-list/${id}/detail");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDetailCase()
  {
    $id = 100;
    $response = $this->call('GET', "book-list/${id}/detail");
    $this->assertEquals(404, $response->status());
  }

  public function testSearchRelationshipCase()
  {
    $book = Author::first();
    $name = $book->name;
    $response = $this->call("POST", "book-list/search", [
      "search" => $name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroyCase()
  {
    $book = BookTransaction::first();
    $id = $book->id;
    $response = $this->call("DELETE", "book-list/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroyCase()
  {
    $id = 123;
    $response = $this->call("DELETE", "book-list/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * @return void
   */
  public function testUpdateSomeCase()
  {
    $faker = Faker\Factory::create();
    $bookTransaction = BookTransaction::latest()->get();
    $book = Book::first()->id;
    $author = Author::first()->id;
    $publisher = Publisher::first()->id;
    $language = Bahasa::first()->id;
    $place = Place::first()->id;
    $subject = Subject::first()->id;

    $response = $this->call("POST", "book-list/update", [
      "update" => [
        $bookTransaction[0]->id => [
          "id_book" => $book,
          "id_author" => $author,
          "id_publisher" => $publisher,
          "id_language" => $language,
          "id_place" => $place,
          "id_subject" => $subject
        ],
        $bookTransaction[1]->id => [
          "id_book" => $book,
          "id_author" => $author,
          "id_publisher" => $publisher,
          "id_language" => $language,
          "id_place" => $place,
          "id_subject" => $subject
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
    $response = $this->call("POST", "book-list/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroySameCase()
  {
    $book = BookTransaction::latest()->get();
    $response = $this->call("POST", "book-list/delete", [
      "delete" => [$book[0]->id, $book[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroySameCase()
  {
    $response = $this->call("POST", "book-list/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testRetrieveDeleteHistoryDataCase()
  {
    $response = $this->call("GET", "book-list/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testReturnDeleteHistoryDataCase()
  {
    $book = BookTransaction::onlyTrashed()->get();
    $response = $this->call("PUT", "book-list/{$book[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testDeleteHistoryDataCase()
  {
    $book = BookTransaction::onlyTrashed()->get();
    $response = $this->call("DELETE", "book-list/{$book[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataCase()
  {
    $response = $this->call("PUT", "book-list/restore");
    $this->assertEquals(200, $response->status());
  }
}
