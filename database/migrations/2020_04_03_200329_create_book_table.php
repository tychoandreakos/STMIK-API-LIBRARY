<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('book', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('title', 250)->nullable(false);
      $table->string("edition", 250)->nullable(true);
      $table->string("isbn", 100)->nullable(false);
      $table->date("release_date")->nullable(true);
      $table
        ->integer("length")
        ->nullable(false)
        ->default(0);
      $table->string("file_image")->nullable(true);
      $table->string("file_name")->nullable(true);
      $table->integer("file_size")->nullable(true);

      $table->text("description")->nullable(true);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('book');
  }
}
