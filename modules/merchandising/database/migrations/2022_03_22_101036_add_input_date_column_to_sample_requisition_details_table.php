<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInputDateColumnToSampleRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_requisition_details', function (Blueprint $table) {
            $table->date('input_date')->nullable()->after('submission_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_requisition_details', function (Blueprint $table) {
            $table->dropColumn('input_date');
        });
    }
}