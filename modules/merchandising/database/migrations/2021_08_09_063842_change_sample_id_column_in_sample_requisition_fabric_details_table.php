<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSampleIdColumnInSampleRequisitionFabricDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_requisition_fabric_details', function (Blueprint $table) {
            $table->json('sample_id')->change();
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
            $table->unsignedInteger('sample_id')->change();
        });
    }
}
