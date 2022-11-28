<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_stock_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('yarn_count_id');
            $table->unsignedInteger('yarn_composition_id');
            $table->unsignedInteger('yarn_type_id');
            $table->string('yarn_color')->nullable();
            $table->string('yarn_lot')->nullable();
            $table->unsignedInteger('uom_id');
            $table->string('receive_qty', 20)->default('0');
            $table->string('receive_return_qty', 20)->default('0');
            $table->string('issue_qty', 20)->default('0');
            $table->string('issue_return_qty', 20)->default('0');
            $table->string('balance', 20)->default('0');
            $table->string('balance_amount', 20)->default('0');
            $table->string('receive_amount', 20)->default('0');
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
        Schema::dropIfExists('yarn_stock_summaries');
    }
}
