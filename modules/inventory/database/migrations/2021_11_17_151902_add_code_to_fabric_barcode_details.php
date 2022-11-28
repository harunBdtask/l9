<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeToFabricBarcodeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_barcode_details', function (Blueprint $table) {
            $table->string('code', 60)->nullable()->after('fabric_receive_detail_id');
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
            $table->dropColumn('code');
        });
    }
}
