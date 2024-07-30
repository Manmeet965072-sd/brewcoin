<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            //
            $table->string('bank_name')->after('user_id')->nullable();
            $table->enum('account_type', ['savings', 'current'])->comment('savings/current')->after('ifsc_code')->nullable();
            $table->boolean('is_primary')->default(1)->after('account_type');
            $table->boolean('is_active')->default(1)->after('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            //
        });
    }
}
