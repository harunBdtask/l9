<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRollIdToSubGreyStoreBarcodeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_grey_store_barcode_details', function (Blueprint $table) {
            $table->string('roll_id')->after('sub_grey_store_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_grey_store_barcode_details', function (Blueprint $table) {
            $table->dropColumn('roll_id');
        });
    }
}
