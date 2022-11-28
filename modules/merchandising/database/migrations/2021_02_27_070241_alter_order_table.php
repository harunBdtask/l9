<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('budget_components');
        Schema::dropIfExists('budget_direct_fabric_components');
        Schema::dropIfExists('budget_dyeing_components');
        Schema::dropIfExists('budget_fabric_booking');
        Schema::dropIfExists('budget_gray_fabric_components');
        Schema::dropIfExists('budget_knitting_components');
        Schema::dropIfExists('budget_masters');
        Schema::dropIfExists('budget_others_components');
        Schema::dropIfExists('budget_po_details');
        Schema::dropIfExists('budget_trims_accessorics_components_details');
        Schema::dropIfExists('budget_trims_accessories_components');
        Schema::dropIfExists('budget_weaving_components');
        Schema::dropIfExists('budget_yarn_components');
        Schema::dropIfExists('pre_budgets');
        Schema::dropIfExists('pre_budget_details');
        Schema::dropIfExists('pre_budget_fabric_costs');
        Schema::dropIfExists('pre_budget_others_costs');
        Schema::dropIfExists('pre_budget_trims_cost');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
