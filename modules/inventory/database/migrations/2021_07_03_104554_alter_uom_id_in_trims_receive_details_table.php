<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUomIdInTrimsReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_receive_details', function (Blueprint $table) {
            $table->unsignedInteger('uom_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_receive_details', function (Blueprint $table) {
            $table->unsignedInteger('uom_id')->nullable(false)->change();
        });
    }
}
