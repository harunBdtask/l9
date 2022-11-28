<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsInvTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gs_inv_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('brand_id')->nullable();
            $table->float('qty')->nullable();
            $table->float('rate')->nullable();
            $table->date('trn_date')->nullable();
            $table->string('trn_type', 10)->index();
            $table->unsignedInteger('trn_with')->nullable();
            $table->unsignedInteger('voucher_id')->nullable();
            $table->string('store', 20)->index();
            $table->string('model')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gs_inv_transactions');
    }
}
