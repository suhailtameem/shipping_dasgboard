<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiversTable extends Migration
{
    public function up()
    {
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->string('cid')->nullable();
            $table->string('first')->nullable();
            $table->string('last')->nullable();
            $table->string('full')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('prof_id_img')->nullable();
            $table->boolean('verify_id')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receivers');
    }
}
