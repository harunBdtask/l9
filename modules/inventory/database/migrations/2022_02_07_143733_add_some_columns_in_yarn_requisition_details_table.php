<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsInYarnRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_requisition_details', function (Blueprint $table) {
            $table->unsignedInteger('store_id')->after('supplier_id')->nullable();
            $table->unsignedInteger('uom_id')->after('yarn_composition_id')->nullable();
            $table->string('yarn_color')->after('yarn_lot')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_requisition_details', function (Blueprint $table) {
            $table->dropColumn('store_id');
            $table->dropColumn('uom_id');
            $table->dropColumn('yarn_color');
        });
    }
}
