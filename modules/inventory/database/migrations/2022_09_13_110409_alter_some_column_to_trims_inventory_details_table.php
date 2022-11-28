<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSomeColumnToTrimsInventoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_inventory_details', function (Blueprint $table) {
            $table->date('receive_date')->nullable()->change();
            $table->string('color_id')->nullable()->change();
            $table->string('size_id')->nullable()->change();
            $table->string('approval_shade_code')->nullable()->change();
            $table->string('delivery_swatch')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_inventory_details', function (Blueprint $table) {
            $table->date('receive_date')->nullable(false)->change();
            $table->string('color_id')->nullable(false)->change();
            $table->string('size_id')->nullable(false)->change();
            $table->string('approval_shade_code')->nullable(false)->change();
            $table->string('delivery_swatch')->nullable(false)->change();
        });
    }
}
