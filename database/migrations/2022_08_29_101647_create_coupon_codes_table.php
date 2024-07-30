<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code');
            $table->string('description')->nullable();
            $table->integer('supply')->comment('0 for unlimited');
            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->float('discount_value');
            $table->integer('frequency')->default(1);
            $table->boolean('is_active')->default(1);
            $table->timestamp('valid_upto')->nullable();
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
        Schema::dropIfExists('coupon_codes');
    }
}
