<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNewBudgetFabricBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

//        Schema::table('budget_fabric_booking', function (Blueprint $table) {
//            $table->dropColumn('uom_id');
//            $table->string('pantone_no')->nullable();
//            DB::statement('ALTER TABLE budget_fabric_booking CHANGE  actual_req_qty actual_req_qty DOUBLE(9,5)');
//            DB::statement('ALTER TABLE budget_fabric_booking CHANGE  total_fabric_qty total_fabric_qty DOUBLE(9,5)');
//        });
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
