<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIssueReturnTypeToFabricIssueReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_issue_return_details', function (Blueprint $table) {
            $table->enum('issue_return_type', ['manual', 'barcode'])->nullable()
                ->comment('manual, barcode')
                ->after('fabric_issue_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_issue_return_details', function (Blueprint $table) {
            $table->dropColumn('issue_return_type');
        });
    }
}
