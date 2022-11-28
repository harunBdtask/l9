<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTargetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_target_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_target_id');
            $table->string('month', 20);
            $table->string('target')->nullable();
            $table->string('value')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->date('deleted_at')->nullable();

            $table->foreign('sales_target_id')->references('id')->on('sales_targets')->onDelete('cascade');
            ;
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            ;

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
        Schema::dropIfExists('sales_target_details');
    }
}
