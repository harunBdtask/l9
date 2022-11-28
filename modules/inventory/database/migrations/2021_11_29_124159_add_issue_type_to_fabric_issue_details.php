<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIssueTypeToFabricIssueDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_issue_details', function (Blueprint $table) {
            $table->enum('issue_type', ['manual', 'barcode'])->nullable()
                ->comment('manual, barcode')
                ->after('fabric_barcode_detail_id');
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
            $table->dropColumn('issue_type');
        });
    }
}
