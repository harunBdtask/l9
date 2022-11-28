<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetTrimsAccessoricsComponentsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_trims_accessorics_components_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trims_budget_id');
            $table->unsignedInteger('budget_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('buyer_id');
            $table->string('color_type');
            $table->string('size_type');
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('size_id');
            $table->double('quantity');
            $table->double('extra_percentage_by_size');
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
        Schema::dropIfExists('budget_trims_accessorics_components_details');
    }
}
