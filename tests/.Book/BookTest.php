<?php

use App\Book;

class BookTest extends TestCase
{
  /**
   * @return void
   */
  public function testIndexCase()
  {
    $response = $this->call('GET', 'book');
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testStoreCase()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'book', [
      "title" => $faker->title,
      "edition" => $faker->word,
      "isbn" => "2309-23092-29302",
      "release_date" => "2012/02/02",
      "length" => 500,
      "file_image" => "{$faker->word}.jpg",
      'file_name' => "{$faker->word}.pdf",
      "file_size" => $faker->randomNumber(2, false),
      'description' => $faker->sentence(6, true)
    ]);

    $this->assertEquals(201, $response->status());
  }

  private function isbn($faker)
  {
    $isbn = array_fill(0, 3, $faker->randomNumber(3, false));
    return join("-", $isbn);
  }

  /**
   * @return void
   */
  public function testFailedValidationStoreCase()
  {
    $response = $this->call('POST', 'book');
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDetailCase()
  {
    $id = Book::first()->id;
    $response = $this->call('GET', "book/${id}/detail");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDetailCase()
  {
    $id = 100;
    $response = $this->call('GET', "book/${id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * @return void
   */

  public function testSearchCase()
  {
    $book = Book::first();
    $name = $book->title;
    $response = $this->call("POST", "book/search", [
      "search" => $name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedSearchCase()
  {
    $search = "tidak ditemukan";
    $response = $this->call("POST", "book/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroyCase()
  {
    $book = Book::first();
    $id = $book->id;
    $response = $this->call("DELETE", "book/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroyCase()
  {
    $id = 123;
    $response = $this->call("DELETE", "book/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * @return void
   */
  public function testUpdateSomeCase()
  {
    $faker = Faker\Factory::create();
    $book = Book::latest()->get();

    $response = $this->call("POST", "book/update", [
      "update" => [
        $book[0]->id => [
          "title" => $faker->title,
          "edition" => $faker->word,
          "isbn" => $this->isbn($faker),
          "release_date" => $faker->date($format = 'Y/m/d', $max = 'now'),
          "length" => $faker->randomNumber(3, false),
          "file_image" => "{$faker->word}.jpg",
          'file_name' => "{$faker->word}.pdf",
          "file_size" => $faker->randomNumber(2, false),
          'description' => $faker->sentence(6, true)
        ],
        $book[1]->id => [
          "title" => $faker->title,
          "edition" => $faker->word,
          "isbn" => $this->isbn($faker),
          "release_date" => $faker->date($format = 'Y/m/d', $max = 'now'),
          "length" => $faker->randomNumber(3, false),
          "file_image" => "{$faker->word}.jpg",
          'file_name' => "{$faker->word}.pdf",
          "file_size" => $faker->randomNumber(2, false),
          'description' => $faker->sentence(6, true)
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
    $response = $this->call("POST", "book/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testDestroySameCase()
  {
    $book = Book::latest()->get();
    $response = $this->call("POST", "book/delete", [
      "delete" => [$book[0]->id, $book[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testFailedDestroySameCase()
  {
    $response = $this->call("POST", "book/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * @return void
   */
  public function testRetrieveDeleteHistoryDataCase()
  {
    $response = $this->call("GET", "book/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testReturnDeleteHistoryDataCase()
  {
    $book = Book::onlyTrashed()->get();
    $response = $this->call("PUT", "book/{$book[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * @return void
   */
  public function testDeleteHistoryDataCase()
  {
    $book = Book::onlyTrashed()->get();
    $response = $this->call("DELETE", "book/{$book[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataCase()
  {
    $response = $this->call("PUT", "book/restore");
    $this->assertEquals(200, $response->status());
  }
}
