<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessLossToYarnPurchaseOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_purchase_order_details', function (Blueprint $table) {
            $table->string('process_loss')->nullable();
            $table->string('total_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_purchase_order_details', function (Blueprint $table) {
            $table->dropColumn([
                'process_loss',
                'total_amount'
            ]);
        });
    }
}
