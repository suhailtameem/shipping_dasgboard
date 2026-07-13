<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_requests', function (Blueprint $table) {
            $table->id();
            $table->string('getway')->nullable();//1-admin 2-user 3-other
            $table->string('getwayType')->nullable();//1-web 2-mobile 3-other

            $table->string('cid')->nullable();
            $table->string('rid')->nullable();

            $table->string('tno')->nullable();
            $table->string('shid')->nullable();
            $table->string('sh_type')->nullable();
            $table->string('containerized')->nullable();
            $table->string('delivered_at')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('clearnce')->nullable()->default('1');// true/false
            $table->string('situation')->nullable();

            $table->string('req_status')->nullable();
            $table->string('total_weight')->nullable();
            $table->string('total_price')->nullable();
            $table->string('Comment')->nullable();

            $table->string('updated_by')->nullable();
            $table->string('wsid')->nullable();
            $table->string('step')->nullable();

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
        Schema::dropIfExists('shipping_requests');
    }
}
