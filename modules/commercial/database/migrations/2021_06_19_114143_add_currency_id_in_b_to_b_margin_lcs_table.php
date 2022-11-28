<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyIdInBToBMarginLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b_to_b_margin_lcs', function (Blueprint $table) {
            $table->unsignedInteger('currency_id')->nullable()->after('lc_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_to_b_margin_lcs', function (Blueprint $table) {
            $table->dropColumn('currency_id');
        });
    }
}
