<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInPriceAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_alerts', function (Blueprint $table) {
            //
            $table->string('coin_id')->change();
            $table->string('target_price')->change();
            $table->string('currency')->after('target_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_alerts', function (Blueprint $table) {
            //
        });
    }
}
