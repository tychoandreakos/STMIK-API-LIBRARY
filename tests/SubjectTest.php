<?php

use App\Subject;

class SubjectTest extends TestCase
{
  /**
   * Testing untuk menampilkan hasil SUBJECT.
   *
   *  @return void
   */
  public function testGetDataSubject()
  {
    $response = $this->call('GET', 'subject');
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing untuk menyimpan data kedalam database SUBJECT.
   *
   *  @return void
   */
  public function testStoreSubject()
  {
    $faker = Faker\Factory::create();
    $response = $this->call('POST', 'subject', [
      'name' => $faker->name,
      'type' => $faker->word
    ]);

    $this->assertEquals(201, $response->status());
  }

  /**
   * Testing untuk gagal menyimpan data kedalam database SUBJECT.
   * Data untk metode post dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *  @return void
   */
  public function testFailedStoreSubject()
  {
    $response = $this->call('POST', 'subject');
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan update SUBJECT.
   *
   *  @return void
   */
  public function testUpdateSubject()
  {
    $faker = Faker\Factory::create();
    $subject = Subject::first();
    $id = $subject->id;
    $response = $this->call('PUT', "subject/{$id}/edit", [
      'name' => $faker->name,
      'type' => $faker->word
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan update SUBJECT.
   * Data untk metode update dikonsongkan. Hal ini dilakukan untuk
   * Melakukan cek pada method validasi
   *
   *  @return void
   */
  public function testFailedUpdateSubject()
  {
    $subject = Subject::first();
    $id = $subject->id;
    $response = $this->call('PUT', "subject/{$id}/edit");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan request terhadap detail SUBJECT.
   *
   * @return void
   */
  public function testGetDetailSubject()
  {
    $subject = Subject::first();
    $id = $subject->id;
    $response = $this->call('GET', "subject/{$id}/detail");
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
  public function testFailedGetDetailSubject()
  {
    $id = '12345';
    $response = $this->call('GET', "subject/{$id}/detail");
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika mencari data PUBLISHER
   *
   * @return void
   */
  public function testSearchSubject()
  {
    $subject = Subject::first();
    $name = $subject->name;
    $response = $this->call("POST", "subject/search", [
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
  public function testFailedSearchSubject()
  {
    $search = "tidak ada";
    $response = $this->call("POST", "subject/search", [
      "search" => $search
    ]);
    $this->assertEquals(404, $response->status());
  }

  /**
   * Testing ketika melakukan hapus data SUBJECT.
   *
   * @return void
   */
  public function testDestroySubject()
  {
    $subject = Subject::first();
    $id = $subject->id;
    $response = $this->call("DELETE", "subject/{$id}/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan hapus data SUBJECT.
   *
   * @return void
   */
  public function testFailedDestroySubject()
  {
    $id = 123;
    $response = $this->call("DELETE", "subject/{$id}/delete");
    $this->assertEquals(500, $response->status());
  }

  /**
   * Testing ketika melakukan update lebih dari satu subject.
   *
   * @return void
   */
  public function testUpdateSomeSubject()
  {
    $faker = Faker\Factory::create();
    $subject = Subject::all();

    $response = $this->call("POST", "subject/update", [
      "update" => [
        $subject[0]->id => [
          "name" => $faker->name,
          "type" => $faker->word
        ],
        $subject[1]->id => [
          "name" => $faker->name,
          "type" => $faker->word
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
  public function testFailedUpdateSomeSubject()
  {
    $response = $this->call("POST", "subject/update");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testDestroySameSubject()
  {
    $subject = Subject::all();
    $response = $this->call("POST", "subject/delete", [
      "delete" => [$subject[0]->id, $subject[1]->id]
    ]);
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika gagal melakukan delete lebih dari satu subject.
   *
   * @return void
   */
  public function testFailedDestroySameSubject()
  {
    $response = $this->call("POST", "subject/delete");
    $this->assertEquals(400, $response->status());
  }

  /**
   * Testing ketika melihat data yang sudah terhapus.
   *
   * @return void
   */
  public function testRetrieveDeleteHistoryDataSubject()
  {
    $response = $this->call("GET", "subject/delete");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika mengembalikan item yang sudah dihapus.
   *
   * @return void
   */
  public function testReturnDeleteHistorySubject()
  {
    $subject = Subject::onlyTrashed()->get();
    $response = $this->call("PUT", "subject/{$subject[0]->id}/restore");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin menghapus item secara permanent.
   *
   * @return void
   */
  public function testDeleteHistoryDataSubject()
  {
    $subject = Subject::onlyTrashed()->get();
    $response = $this->call("DELETE", "subject/{$subject[0]->id}/destroy");
    $this->assertEquals(200, $response->status());
  }

  /**
   * Testing ketika ingin mengembalikan seluruh data yang sudah terhapus.
   *
   * @return void
   */
  public function testReturnAllDeleteHistoryDataSubject()
  {
    $response = $this->call("PUT", "subject/restore");
    $this->assertEquals(200, $response->status());
  }
}
