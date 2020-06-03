<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingSexTableToMember extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('member', function (Blueprint $table) {
      $table
        ->enum('sex', [0, 1])
        ->nullable(false)
        ->after('name');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('member', function (Blueprint $table) {
      //
    });
  }
}
