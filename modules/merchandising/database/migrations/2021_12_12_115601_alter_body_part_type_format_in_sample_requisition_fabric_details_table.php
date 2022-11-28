<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBodyPartTypeFormatInSampleRequisitionFabricDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_requisition_fabric_details', function (Blueprint $table) {
            $table->string('body_part_type')->nullable()->change();
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
            $table->unsignedInteger('body_part_type')->nullable()->change();
        });
    }
}
