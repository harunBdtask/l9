<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomQtyInTrims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
//            $table->string('custom_qty')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
//            $table->dropColumn('custom_qty');
        });
    }
}
