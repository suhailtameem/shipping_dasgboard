<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sh_destinations', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('destinations')->nullable();
            $table->string('ar')->nullable();
            $table->string('status')->nullable();
            $table->string('wsid')->nullable();
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
        Schema::dropIfExists('sh_destinations');
    }
}
