<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_stock_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('style_id');
            $table->unsignedInteger('body_part_id');
            $table->unsignedInteger('color_type_id');
            $table->unsignedInteger('color_id');
            $table->string('construction');
            $table->string('fabric_description')->nullable();
            $table->unsignedInteger('uom_id');
            $table->unsignedBigInteger('fabric_composition_id');
            $table->string('dia', 10);
            $table->string('gsm', 10);
            $table->string('receive_qty', 20)->default('0');
            $table->string('receive_return_qty', 20)->default('0');
            $table->string('issue_qty', 20)->default('0');
            $table->string('issue_return_qty', 20)->default('0');
            $table->string('balance', 20)->default('0');
            $table->string('balance_amount', 20)->default('0');
            $table->string('receive_amount', 20)->default('0');
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
        Schema::dropIfExists('fabric_stock_summaries');
    }
}
