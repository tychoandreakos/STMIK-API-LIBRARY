<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookTransaction extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('book_transaction', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid("id_book")->nullable(false);
      $table->uuid("id_author")->nullable(false);
      $table->uuid("id_publisher")->nullable(false);
      $table->uuid("id_language")->nullable(false);
      $table->uuid("id_place")->nullable(false);
      $table->uuid("id_subject")->nullable(false);
      $table->timestamps();
      $table->softDeletes();

      $table
      ->foreign("id_book")
      ->references("id")
      ->on("book");
      $table
        ->foreign("id_author")
        ->references("id")
        ->on("author");
      $table
        ->foreign("id_publisher")
        ->references("id")
        ->on("publisher");
      $table
        ->foreign("id_language")
        ->references("id")
        ->on("bahasa");
      $table
        ->foreign("id_place")
        ->references("id")
        ->on("place");
      $table
        ->foreign("id_subject")
        ->references("id")
        ->on("subject");
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('book_transaction');
  }
}
