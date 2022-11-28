<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsInBtbMarginLcTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b_to_b_margin_lcs', function (Blueprint $table) {
            $table->integer('amended')->default(0)->after('remarks');
        });

        Schema::table('b_to_b_margin_lc_amendments', function (Blueprint $table) {
            $table->string('lc_value', 30)->nullable()->after('amendment_no');
            DB::statement("ALTER TABLE `b_to_b_margin_lc_amendments` CHANGE `value_changed_by` `value_changed_by` VARCHAR(10) NULL DEFAULT NULL;");
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
            $table->dropColumn('lc_value');
            DB::statement("ALTER TABLE `b_to_b_margin_lc_amendments` CHANGE `value_changed_by` `value_changed_by` TINYINT(4) NULL;");
        });
        Schema::table('b_to_b_margin_lcs', function (Blueprint $table) {
            $table->dropColumn('amended');
        });
    }
}
