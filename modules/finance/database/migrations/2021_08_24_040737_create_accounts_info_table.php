<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accounts_id')->nullable();
            $table->unsignedBigInteger('parent_account_id')->nullable();
            $table->unsignedBigInteger('group_account_id')->nullable();
            $table->unsignedBigInteger('control_account_id')->nullable();
            $table->unsignedBigInteger('ledger_account_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('accounts_info');
    }
}
