<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactoryIdAndBrandNameToV3TrimsStoreReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('v3_trims_store_receive_details', function (Blueprint $table) {
            $table->unsignedBigInteger('factory_id')->after('transaction_date');
            $table->dropColumn('brand_id');
            $table->string('brand_name')->nullable()->after('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_trims_store_receive_details', function (Blueprint $table) {
            $table->dropColumn('factory_id');
            $table->dropColumn('brand_name');
            $table->unsignedBigInteger('brand_id')->nullable()->after('supplier_id');
        });
    }
}
