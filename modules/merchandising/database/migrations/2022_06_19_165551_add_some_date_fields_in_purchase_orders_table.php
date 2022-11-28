<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeDateFieldsInPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->integer('production_lead_time')->after('lead_time')->nullable();
            $table->date('pi_bunch_budget_date')->after('production_lead_time')->nullable();
            $table->date('ex_bom_handover_date')->after('pi_bunch_budget_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'production_lead_time',
                'pi_bunch_budget_date',
                'ex_bom_handover_date',
            ]);
        });
    }
}
