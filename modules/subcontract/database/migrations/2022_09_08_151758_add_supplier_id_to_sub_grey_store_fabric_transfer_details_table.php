<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierIdToSubGreyStoreFabricTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_grey_store_fabric_transfer_details', function (Blueprint $table) {
            $table->unsignedBigInteger('from_supplier_id')->after('from_order_id');
            $table->unsignedBigInteger('to_supplier_id')->after('to_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_grey_store_fabric_transfer_details', function (Blueprint $table) {
            $table->dropColumn('from_supplier_id');
            $table->dropColumn('to_supplier_id');
        });
    }
}
