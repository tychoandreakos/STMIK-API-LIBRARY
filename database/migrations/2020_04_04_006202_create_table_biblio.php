<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBiblio extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('biblio', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string("pattern_id", 100)->unique();
      $table->uuid('id_book_transaction');
      $table->unsignedBigInteger("id_pattern");
      $table->string("id_classification", 3);
      $table->uuid("id_location");
      $table->uuid("id_gmd");
      $table->uuid("id_koleksi");
      $table->timestamps();
      $table->softDeletes();

      $table
        ->foreign("id_book_transaction")
        ->references("id")
        ->on("book_transaction");

      $table
        ->foreign("id_pattern")
        ->references("id")
        ->on("pattern");

      $table
        ->foreign("id_classification")
        ->references("id")
        ->on("classification_list");
      $table
        ->foreign("id_location")
        ->references("id")
        ->on("location");
      $table
        ->foreign("id_gmd")
        ->references("id")
        ->on("gmd");
      $table
        ->foreign("id_koleksi")
        ->references("id")
        ->on("koleksi");
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('biblio');
  }
}
