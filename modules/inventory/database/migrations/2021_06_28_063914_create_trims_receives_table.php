<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_receives', function (Blueprint $table) {
            $table->id();
            $table->string('uniq_id');
            $table->string('receive_basic', 30);
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('store_id');
            $table->date('receive_date');
            $table->string('challan_no');
            $table->unsignedInteger('supplier_id');
            $table->string('pay_mode', 30)->nullable();
            $table->string('source', 20)->nullable();
            $table->string('lc_no', 30)->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->date('challan_date')->nullable();
            $table->string('exchange_rate', 20)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('trims_receives');
    }
}
