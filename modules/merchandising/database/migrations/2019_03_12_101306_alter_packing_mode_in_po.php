<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackingModeInPo extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
//            $table->string('packing_mode')->change();
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
//            $table->unsignedInteger('packing_mode')->change();
        });
    }
}
