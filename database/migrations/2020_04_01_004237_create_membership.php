<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembership extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('member', function (Blueprint $table) {
      $table->bigInteger('id')->primary();
      $table->unsignedBigInteger('membertype_id');
      $table->string('name', 150)->nullable(false);
      $table->date('birthdate')->nullable(true);
      $table->date('member_since')->nullable(false);
      $table->date('expiry_date')->nullable(false);
      $table->string('alamat', 150)->nullable(true);
      $table->string('username', 100)->nullable(false)->unique();
      $table->string('email', 100)->nullable(false)->unique();
      $table->string('password', 200)->nullable(false);
      $table->string('phone', 25)->nullable(true)->unique();
      $table->boolean('pending')->default(false);
      $table->string('image', 150)->nullable(true);
      $table->timestamps();
      $table->softDeletes();

      $table
        ->foreign('membertype_id')
        ->references('id')
        ->on('member_type')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('member');
  }
}
