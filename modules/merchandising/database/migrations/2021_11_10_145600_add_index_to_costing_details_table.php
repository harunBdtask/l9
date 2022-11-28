<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToCostingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('costing_details', function (Blueprint $table) {
            $table->index('price_quotation_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('costing_details', function (Blueprint $table) {
            $table->dropIndex(['price_quotation_id']);
            $table->dropIndex(['type']);
        });
    }
}
