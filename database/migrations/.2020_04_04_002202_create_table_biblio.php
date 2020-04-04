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
      $table->string("pattern_id")->unique();
      $table->string('book');
      $table->string("pattern");
      $table->string("classification");
      $table->string("location");
      $table->string("gmd");
      $table->string("koleksi");
      $table->timestamps();
      $table->softDeletes();

      $table
        ->foreign("book")
        ->references("id")
        ->on("book");

      $table
        ->foreign("pattern")
        ->references("id")
        ->on("pattern");

      $table
        ->foreign("classification")
        ->references("id")
        ->on("classification");
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
