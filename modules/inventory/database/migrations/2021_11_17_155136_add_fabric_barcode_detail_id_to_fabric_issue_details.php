<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFabricBarcodeDetailIdToFabricIssueDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_issue_details', function (Blueprint $table) {
            $table->unsignedInteger('fabric_barcode_detail_id')->nullable()->after('fabric_receive_details_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_issue_details', function (Blueprint $table) {
            $table->dropColumn('fabric_barcode_detail_id');
        });
    }
}
