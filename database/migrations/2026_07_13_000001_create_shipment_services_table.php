<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentServicesTable extends Migration
{
    public function up()
    {
        Schema::create('shipment_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->unsignedBigInteger('sub_list_id')->nullable();
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->foreign('shipment_id')
                  ->references('id')
                  ->on('shipping_requests')
                  ->onDelete('cascade');

            $table->foreign('sub_list_id')
                  ->references('id')
                  ->on('sub_lists')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipment_services');
    }
}
