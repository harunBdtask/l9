<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactoryIdAndTransactionDateToV3TrimsStoreReceiveReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('v3_trims_store_receive_return_details', function (Blueprint $table) {
            $table->unsignedBigInteger('factory_id')->after('return_to');
            $table->date('transaction_date')->after('style_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_trims_store_receive_return_details', function (Blueprint $table) {
            $table->dropColumn('factory_id');
            $table->dropColumn('transaction_date');
        });
    }
}
