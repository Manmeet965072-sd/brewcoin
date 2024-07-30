<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageKeyValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_key_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('key_id')->unsigned();
            $table->foreign('key_id')->references('id')->on('keys')->onDelete('cascade')->onDelete('cascade');
            $table->bigInteger('lang_id')->unsigned();
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade')->onDelete('cascade');
            $table->string('value');
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
        Schema::dropIfExists('language_key_values');
    }
}
