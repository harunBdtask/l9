<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcDiaDiaTypeGsmToFabricReceiveReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_receive_return_details', function (Blueprint $table) {
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
        Schema::table('fabric_receive_return_details', function (Blueprint $table) {
            $table->dropColumn('ac_dia');
            $table->dropColumn('ac_gsm');
            $table->dropColumn('ac_dia_type');
        });
    }
}
