<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificationList extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('classification_list', function (Blueprint $table) {
      $table->bigInteger('id')->primary();
      $table->unsignedBigInteger('name_id');
      $table
        ->string("name", 200)
        ->nullable(false)
        ->unique();
      $table->timestamps();

      $table
        ->foreign("name_id")
        ->references("id")
        ->on("classification_name");
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('classification_list');
  }
}
