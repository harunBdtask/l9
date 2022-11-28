<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmendmentDateInBToBMarginLcAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b_to_b_margin_lc_amendments', function (Blueprint $table) {
            $table->date('amendment_date')->nullable()->after('amendment_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_to_b_margin_lc_amendments', function (Blueprint $table) {
            $table->dropColumn('amendment_date');
        });
    }
}
