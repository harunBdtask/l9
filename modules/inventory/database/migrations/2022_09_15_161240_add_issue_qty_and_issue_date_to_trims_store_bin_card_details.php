<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIssueQtyAndIssueDateToTrimsStoreBinCardDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_bin_card_details', function (Blueprint $table) {
            $table->string('issue_qty')->nullable();
            $table->string('issue_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_store_bin_card_details', function (Blueprint $table) {
            $table->dropColumn([
                'issue_qty',
                'issue_date'
            ]);
        });
    }
}
