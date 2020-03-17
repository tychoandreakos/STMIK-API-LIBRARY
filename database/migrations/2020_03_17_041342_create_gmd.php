<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmd extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('gmd', function (Blueprint $table) {
      // sumber: https://dev.to/wilburpowery/easily-use-uuids-in-laravel-45be
      $table->uuid('id')->primary(); // membuat ID menggunakan UUID agar lebih aman.
      $table->string('gmd_code', 25)->unique();
      $table->string('gmd_name', 50);
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
    Schema::dropIfExists('gmd');
  }
}
