<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableBudgetFabricBookingAddShortReqQuantityColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('budget_fabric_booking', function (Blueprint $table) {
//            $table->addColumn('float','short_req_qty')->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('budget_fabric_booking', function (Blueprint $table) {
//            $table->removeColumn('short_req_qty');
//        });
    }
}
