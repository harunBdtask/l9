<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartWiseQtyInMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_direct_fabric_components', function (Blueprint $table) {
            $table->unsignedInteger('part_wise_qty');
            $table->float('fabric_consumption_qty');
        });

        Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
            $table->unsignedInteger('gray_fabric_part_wise_qty');
            $table->float('gray_fabric_consumption_qty');
        });
        Schema::table('budget_yarn_components', function (Blueprint $table) {
            $table->unsignedInteger('yarn_part_wise_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
