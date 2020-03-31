<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMemberType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->integer('limit_loan')->nullable(false);
            $table->integer('loan_periode')->nullable(false);
            $table->integer('membership_periode')->nullable(false);
            $table->double("fines")->nullable(false);
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
        Schema::dropIfExists('member_type');
    }
}
