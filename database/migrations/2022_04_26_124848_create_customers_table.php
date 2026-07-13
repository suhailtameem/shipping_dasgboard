<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first')->nullable();
            $table->string('last')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->text('location')->nullable();
            $table->string('id_proff_image')->nullable();
            $table->string('password')->nullable();
            $table->string('type')->nullable();
            $table->string('ws')->nullable();
            $table->string('last_login')->nullable();
            $table->string('use')->nullable();
            $table->string('token')->nullable();
            $table->string('lang')->nullable();
            $table->string('legals')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
