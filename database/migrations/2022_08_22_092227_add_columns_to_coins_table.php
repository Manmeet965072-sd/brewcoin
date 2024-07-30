<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coins', function (Blueprint $table) {
            //
            $table->dropColumn('currency');
            $table->string('symbol')->after('name');
            $table->string('coin_id')->after('symbol');
            $table->string('icon')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coins', function (Blueprint $table) {
            //
        });
    }
}
