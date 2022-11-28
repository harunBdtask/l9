<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToCuttingInventoryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cutting_inventory_challans', function (Blueprint $table) {
            $table->string('total_rib_size')->nullable();
            $table->string('rib_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cutting_inventory_challans', function (Blueprint $table) {
            $table->dropColumn('total_rib_size');
            $table->dropColumn('rib_comments');
        });
    }
}
