<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToYarnPurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_purchase_order_details', function (Blueprint $table) {
            $table->string('fabric_description')->nullable()->after('delivery_end_date');
            $table->unsignedBigInteger('fabric_composition_id')->nullable()->after('fabric_description');
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
            $table->dropColumn(['fabric_composition_id', 'fabric_description']);
        });
    }
}
