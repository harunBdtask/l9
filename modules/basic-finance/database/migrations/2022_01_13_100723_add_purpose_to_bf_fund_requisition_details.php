<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurposeToBfFundRequisitionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_fund_requisition_details', function (Blueprint $table) {
            $table->unsignedInteger('purpose_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_fund_requisition_details', function (Blueprint $table) {
            $table->dropColumn('purpose_id');
        });
    }
}
