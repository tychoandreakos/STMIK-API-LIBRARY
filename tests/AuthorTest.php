<?php

use App\Author;

class AuthorTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil AUTHOR.
   *
   *  @return void
   */
  public function testGetDataAuthor()
  {
    $response = $this->call('GET', 'author');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database AUTHOR.
   *
   *  @return void
   */
  public function testStoreAuthor()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'author', [
      'name' => $faker->name
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database AUTHOR.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStoreAuthor()
  {
    $response = $this->call('POST', 'author');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update AUTHOR.
   *
   *  @return void
   */
  public function testUpdateAuthor()
  {
    $faker = Faker\Factory::create();
    $publisher = Author::first();
    $id = $publisher->id;
    $response = $this->call('PUT', "author/{$id}/edit", [
      'name' => $faker->name
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update AUTHOR.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdateAuthor()
  {
    $publisher = Author::first();
    $id = $publisher->id;
    $response = $this->call('PUT', "author/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail AUTHOR.
   *
   * @return void
   */
  public function testGetDetailAuthor()
  {
    $publisher = Author::first();
    $id = $publisher->id;
    $response = $this->call('GET', "author/{$id}/detail");
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
  public function testFailedGetDetailAuthor()
  {
    $id = '12345';
    $response = $this->call('GET', "author/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data PUBLISHER
   *
   * @return void
   */
  public function testSearchAuthor()
  {
    $publisher = Author::first();
    $name = $publisher->name;
    $response = $this->call("POST", "author/search", [
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
  public function testFailedSearchAuthor()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "author/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data AUTHOR.
   *
   * @return void
   */
  public function testDestroyAuthor()
  {
    $publisher = Author::first();
    $id = $publisher->id;
    $response = $this->call("DELETE", "author/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data AUTHOR.
   *
   * @return void
   */
  public function testFailedDestroyAuthor()
  {
    $id = 123;
    $response = $this->call("DELETE", "author/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSomeAuthor()
  {
    $faker = Faker\Factory::create();
    $publisher = Author::all();

    $response = $this->call("POST", "author/update", [
      "update" => [
        $publisher[0]->id => [
          "name" => $faker->name
        ],
        $publisher[1]->id => [
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
  public function testFailedUpdateSomeAuthor()
  {
    $response = $this->call("POST", "author/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySameAuthor()
  {
    $publisher = Author::all();
    $response = $this->call("POST", "author/delete", [
      "delete" => [$publisher[0]->id, $publisher[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySameAuthor()
  {
    $response = $this->call("POST", "author/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryDataAuthor()
  {
    $response = $this->call("GET", "author/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistoryAuthor()
  {
    $publisher = Author::onlyTrashed()->get();
    $response = $this->call("PUT", "author/{$publisher[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryDataAuthor()
  {
    $publisher = Author::onlyTrashed()->get();
    $response = $this->call("DELETE", "author/{$publisher[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataAuthor()
  {
    $response = $this->call("PUT", "author/restore");
    $this->assertEquals(200, $response->status());
  }
}
