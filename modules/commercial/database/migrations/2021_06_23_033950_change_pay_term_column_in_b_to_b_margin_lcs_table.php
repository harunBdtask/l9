<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePayTermColumnInBToBMarginLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b_to_b_margin_lcs', function (Blueprint $table) {
            $table->string('inco_term', 20)->nullable()->change();
            $table->string('pay_term', 20)->nullable()->change();
        });

        Schema::table('b_to_b_margin_lc_amendments', function (Blueprint $table) {
            $table->string('pay_term', 20)->nullable()->change();
            $table->string('inco_term', 20)->nullable()->change();
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
            $table->string('inco_term', 20)->nullable()->change();
            $table->string('pay_term', 20)->nullable()->change();
        });

        Schema::table('b_to_b_margin_lc_amendments', function (Blueprint $table) {
            $table->string('inco_term', 20)->nullable()->change();
            $table->string('pay_term', 20)->nullable()->change();
        });
    }
}
