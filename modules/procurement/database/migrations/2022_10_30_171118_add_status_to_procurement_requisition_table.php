<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToProcurementRequisitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procurement_requisitions', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0=created, 1=approved, 2=posted, 3=cancelled')->after('priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procurement_requisitions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
