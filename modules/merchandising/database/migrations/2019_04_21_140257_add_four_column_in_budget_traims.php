<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFourColumnInBudgetTraims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
//            $table->unsignedInteger('garments_color')->after('supplier_id');
//            $table->string('measurement')->after('item_id');
//            $table->string('trims_sizes')->after('measurement');
//            $table->unsignedInteger('excess_qty')->after('required_qty');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
//            // $table->dropColumn('garments_color');
//            // $table->dropColumn('trims_sizes');
//            // $table->dropColumn('measurement');
//            $table->dropColumn('excess_qty');
//        });
    }
}
