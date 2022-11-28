<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CostAssumptionDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_assumption_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('cost_assumption_id');
            $table->float('finish_fab_cost', '8', '2');
            $table->float('trims_accessories', '8', '2');
            $table->float('cost_of_manufacturing', '8', '2');
            $table->float('others_cost', '8', '2');
            $table->float('profit_percentage', '8', '2');
            $table->float('item_unit_cost', '8', '2');
            $table->float('set_information', '8', '2');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->unsignedInteger('deleted_by');
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
        Schema::drop('cost_assumption_details');
    }
}
