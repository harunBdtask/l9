<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOperationIdToSubGreyStoreReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_grey_store_receive_details', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_textile_operation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_grey_store_receive_details', function (Blueprint $table) {
            $table->dropColumn('sub_textile_operation_id');
        });
    }
}
