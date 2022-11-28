<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitIdCostCenterConversionRateToJournal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('journal', function (Blueprint $table) {
            $table->unsignedInteger('unit_id')->nullable()->after('trn_type');
            $table->string('cost_center_id')->nullable()->after('unit_id');
            $table->string('currency_id')->nullable()->after('cost_center_id');
            $table->string('conversion_rate')->nullable()->after('currency_id');
            $table->string('fc')->nullable()->after('conversion_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal', function (Blueprint $table) {
            $table->dropColumn('unit_id');
            $table->dropColumn('cost_center_id');
            $table->dropColumn('currency_id');
            $table->dropColumn('conversion_rate');
            $table->dropColumn('fc');
        });
    }
}
