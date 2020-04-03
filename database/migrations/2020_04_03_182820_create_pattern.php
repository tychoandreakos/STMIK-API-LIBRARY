<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePattern extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pattern', function (Blueprint $table) {
      $table->id();
      $table
        ->string('prefix', 10)
        ->default("b")
        ->nullable(false);
      $table
        ->string("suffix", 10)
        ->default("0")
        ->nullable(false);
      $table
        ->string('middle', 50)
        ->nullable(false)
        ->default("00");
      $table->string('last_pattern', 50)->nullable(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pattern');
  }
}
