<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConversionRateInCommercialRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commercial_realizations', function (Blueprint $table) {
            $table->string('conversion_rate', 30)->nullable()->after('bank_ref_bill');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commercial_realizations', function (Blueprint $table) {
            $table->dropColumn('conversion_rate');
        });
    }
}
