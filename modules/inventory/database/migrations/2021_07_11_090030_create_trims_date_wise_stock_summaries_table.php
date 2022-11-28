<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsDateWiseStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_date_wise_stock_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('style_name');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('uom_id');
            $table->date('date');
            $table->string('receive_qty', 20)->default('0');
            $table->string('receive_return_qty', 20)->default('0');
            $table->string('issue_qty', 20)->default('0');
            $table->string('issue_return_qty', 20)->default('0');
            $table->string('rate', 20)->default('0');
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('trims_date_wise_stock_summaries');
    }
}
