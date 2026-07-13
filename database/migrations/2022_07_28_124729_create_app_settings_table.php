<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\appSettings;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('power')->nullable()->default('on');//close app on/off
            $table->string('power_en')->nullable();//close resone EN
            $table->string('power_ar')->nullable();//AR
            $table->string('version')->nullable()->default('1.0');//App version
            $table->string('old')->nullable()->default('on');//close old version on/off
            $table->string('old_en')->nullable();//close old version resone EN
            $table->string('old_ar')->nullable();//AR
            $table->string('link')->nullable();//app playstore update link
            $table->string('legals_en')->nullable();// terms and legals EN
            $table->string('legals_ar')->nullable();// terms and legals Ar
            $table->string('cs')->nullable();// customer services
            $table->string('wsid')->nullable()->default('1');//workstation
            $table->timestamps();
        });

        appSettings::create([]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_settings');
    }
}
