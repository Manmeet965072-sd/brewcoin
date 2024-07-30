<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onDelete('cascade');
            $table->bigInteger('coin_id')->unsigned();
            $table->foreign('coin_id')->references('id')->on('currencies')->onDelete('cascade')->onDelete('cascade');
            $table->float('live_rate');
            $table->float('target_rate')->nullable();
            $table->enum('status', ['Pending', 'Completed', 'Rejected'])->default('Pending');
            $table->enum('purchase_type', ['Buy', 'Sell']);
            $table->float('amount');
            $table->float('qty');
            $table->enum('order_type', ['Instant', 'Limit'])->default('Instant');
            $table->integer('used_coupon_code')->nullable();
            $table->boolean('is_executed')->default(0);
            $table->timestamp('executed_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
