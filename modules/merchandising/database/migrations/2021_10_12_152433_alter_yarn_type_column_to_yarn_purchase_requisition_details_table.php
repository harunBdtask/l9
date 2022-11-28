<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterYarnTypeColumnToYarnPurchaseRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_purchase_requisition_details', function (Blueprint $table) {
            $table->string('yarn_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_purchase_requisition_details', function (Blueprint $table) {
//            $table->dropColumn('yarn_type');
        });
    }
}
