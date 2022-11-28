<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinSmvColumnToPriceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_quotations', function (Blueprint $table) {
            $table->string('fin_smv')->after('cut_smv')->comment('Finishing SMV')->nullable();
            $table->string('fin_eff')->after('cut_eff')->comment('Finishing Efficiency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_quotations', function (Blueprint $table) {
            $table->dropColumn('fin_smv');
            $table->dropColumn('fin_eff');
        });
    }
}
