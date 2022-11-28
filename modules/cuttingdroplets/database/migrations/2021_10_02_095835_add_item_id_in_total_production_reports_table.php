<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemIdInTotalProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('total_production_reports', function (Blueprint $table) {
            $table->unsignedInteger('garments_item_id')->after('order_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('total_production_reports', function (Blueprint $table) {
            $table->dropColumn('garments_item_id');
        });
    }
}
