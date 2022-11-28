<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnittingDyeingCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knitting_dyeing_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pre_budget_id');
            $table->string('type', 20);
            $table->double('quantity');
            $table->double('unit_price');
            $table->double('total');
            $table->string('origin', 50)->default('BD');
            $table->string('shipment_mode', 50)->nullable();
            $table->string('payment_mode', 50)->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('remarks')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('knitting_dyeing_costs');
    }
}
