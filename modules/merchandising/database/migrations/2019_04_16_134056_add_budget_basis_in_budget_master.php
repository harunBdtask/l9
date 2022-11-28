<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBudgetBasisInBudgetMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('budget_masters', function (Blueprint $table) {
//            $table->unsignedInteger('budget_basis')->after('purchase_order_id')->comment = '1 = Piece , 2 = Dozen';
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('budget_masters', function (Blueprint $table) {
//            $table->dropColumn('budget_basis');
//        });
    }
}
