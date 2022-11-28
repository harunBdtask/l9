<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToTrimsOrderToOrderTransfers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_order_to_order_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->after('to_order')->nullable();
            $table->unsignedBigInteger('updated_by')->after('created_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->after('updated_by')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_order_to_order_transfers', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
            $table->dropColumn('deleted_at');
        });
    }
}
