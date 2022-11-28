<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_budgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_number');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('factory_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('pre_budget_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pre_budget_id');
            $table->string('order_no');
            $table->string('image')->nullable();
            $table->string('style');
            $table->unsignedInteger('quantity');
            $table->string('description');
            $table->double('unit_price');
            $table->double('total');
            $table->double('cm');
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
        Schema::dropIfExists('pre_budgets');
        Schema::dropIfExists('pre_budget_details');
    }
}
