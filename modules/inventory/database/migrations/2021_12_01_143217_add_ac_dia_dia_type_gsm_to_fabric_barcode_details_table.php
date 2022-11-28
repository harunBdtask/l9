<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcDiaDiaTypeGsmToFabricBarcodeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_barcode_details', function (Blueprint $table) {
            $table->string('ac_dia', 10)->nullable()->after('dia');
            $table->string('ac_gsm', 10)->nullable()->after('gsm');
            $table->unsignedTinyInteger('ac_dia_type')->nullable()->after('dia_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_barcode_details', function (Blueprint $table) {
            $table->dropColumn('ac_dia');
            $table->dropColumn('ac_gsm');
            $table->dropColumn('ac_dia_type');
        });
    }
}
