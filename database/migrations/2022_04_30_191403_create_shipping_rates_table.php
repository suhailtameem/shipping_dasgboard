<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('shtype')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('weight_from')->nullable();
            $table->string('Weight_to')->nullable();
            $table->string('unit')->nullable();
            $table->string('price')->nullable();
            $table->string('currency')->nullable();
            $table->string('wsid')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('shipping_rates');
    }
}
