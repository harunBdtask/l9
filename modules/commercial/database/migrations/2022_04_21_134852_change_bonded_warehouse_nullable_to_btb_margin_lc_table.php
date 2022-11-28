<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBondedWarehouseNullableToBtbMarginLcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b_to_b_margin_lcs', function (Blueprint $table) {
            $table->unsignedInteger('bonded_warehouse')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_to_b_margin_lcs', function (Blueprint $table) {
            $table->tinyInteger('bonded_warehouse')->nullable(false)->change();
        });
    }
}
