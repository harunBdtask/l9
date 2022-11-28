<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateColumnInSampleRequisitionFabricDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_requisition_fabric_details', function (Blueprint $table) {
            $table->decimal('rate', 8, 2)->after('req_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_requisition_fabric_details', function (Blueprint $table) {
            $table->dropColumn('rate');
        });
    }
}
