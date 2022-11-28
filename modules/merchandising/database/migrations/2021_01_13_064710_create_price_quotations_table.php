<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_id', 20)->nullable()->comment = "qid";
            $table->string('quotation_inquiry_id', 20)->nullable()->comment = "quotation_inquiries table quotation_id";
            $table->string('revised_no')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->string('location', 255)->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('product_department_id')->nullable();
            $table->string('style_name')->nullable();
            $table->string('style_desc')->nullable();
            $table->string('offer_qty')->nullable();
            $table->unsignedInteger('season_id')->nullable();
            $table->tinyInteger('style_uom')->default(1)->comment = "1=Pcs, 2=Set";
            $table->json("item_details")->nullable();
            $table->tinyInteger('costing_per')->default(1)->comment = "1=1 Dzn, 2=1 Pc, 3=2 Dzn, 4=3 Dzn, 5=4 Dzn";
            $table->string('costing_multiplier', 10)->nullable()->comment = "style_uom(pc=1,set=2) * costing_per(1pc=1,1dzn=12,etc)";
            $table->unsignedInteger('buying_agent_id')->nullable();
            $table->string('region')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->unsignedInteger('incoterm_id')->nullable();
            $table->string('incoterm_place')->nullable();
            $table->string('machine_line')->nullable();
            $table->date('quotation_date')->nullable();
            $table->date('op_date')->nullable();
            $table->date('est_shipment_date')->nullable();
            $table->unsignedInteger('color_range_id')->nullable();
            $table->string('prod_line_hr')->nullable();
            $table->string('sew_smv')->nullable()->comment = "Sewing SMV";
            $table->string('cut_smv')->nullable()->comment = "Cutting SMV";
            $table->string('sew_eff')->nullable()->comment = "Sewing Efficiency";
            $table->string('cut_eff')->nullable()->comment = "Cutting Efficiency";
            $table->unsignedInteger('bh_merchant')->nullable()->comment = "Buyers table id";
            $table->tinyInteger('ready_to_approve')->nullable()->comment = "1=Yes, 2=No";
            $table->tinyInteger('status')->nullable()->comment = "1=Pending, 2=Confirm, 3=Approve";
            $table->date('confirm_date')->nullable();
            $table->string('fab_cost', 60)->nullable()->comment = "Fabric Cost";
            $table->string('fab_cost_prcnt', 60)->nullable()->comment = "Fabric Cost Percentage";
            $table->string('trims_cost', 60)->nullable()->comment = "Trims Cost";
            $table->string('trims_cost_prcnt', 60)->nullable()->comment = "Trims Cost Percentage";
            $table->string('embl_cost', 60)->nullable()->comment = "Embellishment Cost";
            $table->string('embl_cost_prcnt', 60)->nullable()->comment = "Embellishment Cost Percentage";
            $table->string('gmt_wash', 60)->nullable()->comment = "Garments Cost";
            $table->string('gmt_wash_prcnt', 60)->nullable()->comment = "Garments Cost Percentage";
            $table->string('comml_cost', 60)->nullable()->comment = "Comml Cost";
            $table->string('comml_cost_prcnt', 60)->nullable()->comment = "Comml Cost Percentage";
            $table->string('lab_cost', 60)->nullable()->comment = "Lab Test Cost";
            $table->string('lab_cost_prcnt', 60)->nullable()->comment = "Lab Test Cost Percentage";
            $table->string('inspect_cost', 60)->nullable()->comment = "Inspection Cost";
            $table->string('inspect_cost_prcnt', 60)->nullable()->comment = "Inspection Cost Percentage";
            $table->string('cm_cost', 60)->nullable()->comment = "CM Cost";
            $table->string('cm_cost_prcnt', 60)->nullable()->comment = "CM Cost Percentage";
            $table->string('freight_cost')->nullable()->comment = "Freight Cost";
            $table->string('freight_cost_prcnt', 60)->nullable()->comment = "Freight Cost Percentage";
            $table->string('currier_cost', 60)->nullable()->comment = "Currier Cost";
            $table->string('currier_cost_prcnt', 60)->nullable()->comment = "Currier Cost Percentage";
            $table->string('certif_cost', 60)->nullable()->comment = "Certif Cost";
            $table->string('certif_cost_prcnt', 60)->nullable()->comment = "Certif Cost Percentage";
            $table->string('common_oh', 60)->nullable()->comment = "Common OH Cost";
            $table->string('common_oh_prcnt', 60)->nullable()->comment = "Common OH Cost Percentage";
            $table->string('total_cost', 60)->nullable()->comment = "Total Cost";
            $table->string('total_cost_prcnt', 60)->nullable()->comment = "Total Cost Percentage";
            $table->string('final_cost_pc_set', 60)->nullable()->comment = "Final Cost PC/Set";
            $table->string('final_cost_pc_set_prcnt', 60)->nullable()->comment = "Final Cost PC/Set Percentage";
            $table->string('asking_profit_pc_set', 60)->nullable()->comment = "Asking Profit PC/Set";
            $table->string('asking_profit_pc_set_prcnt', 60)->nullable()->comment = "Asking Profit PC/Set Percentage";
            $table->string('asking_quoted_pc_set', 60)->nullable()->comment = "Asking Quoted Price PC/Set";
            $table->string('asking_quoted_pc_set_prcnt', 60)->nullable()->comment = "Asking Quoted Price PC/Set Percentage";

            $table->string('revised_price_pc_set', 60)->nullable()->comment = "Revised Price PC/Set";
            $table->string('revised_price_pc_set_prcnt', 60)->nullable()->comment = "Revised Price PC/Set Percentage";

            $table->string('confirm_price_pc_set', 60)->nullable()->comment = "Confirm Price PC/Set";
            $table->string('confirm_price_pc_set_prcnt', 60)->nullable()->comment = "Confirm Price PC/Set Percentage";
            $table->string('price_bef_commn_dzn', 60)->nullable()->comment = "Price Before Comn /Dzn";
            $table->string('price_bef_commn_dzn_prcnt', 60)->nullable()->comment = "Price Before Comn /Dzn Percentage";
            $table->string('prod_cost_dzn', 60)->nullable()->comment = "Prd. Cost /Dzn";
            $table->string('prod_cost_dzn_prcnt', 60)->nullable()->comment = "Prd. Cost /Dzn Percentage";
            $table->string('margin_dzn', 60)->nullable()->comment = "Margin /Dzn";
            $table->string('margin_dzn_prcnt', 60)->nullable()->comment = "Margin /Dzn Percentage";
            $table->string('commi_dzn', 60)->nullable()->comment = "Commi./ Dzn";
            $table->string('commi_dzn_prcnt', 60)->nullable()->comment = "Commi./ Dzn Percentage";
            $table->string('price_with_commn_dzn', 60)->nullable()->comment = "Price with Commn /Dzn";
            $table->string('price_with_commn_dzn_prcnt', 60)->nullable()->comment = "Price with Commn /Dzn Percentage";
            $table->string('price_with_commn_pcs', 60)->nullable()->comment = "Price with Commn /Pcs";
            $table->string('price_with_commn_pcs_prcnt', 60)->nullable()->comment = "Price with Commn /Pcs Percentage";
            $table->string('target_price', 60)->nullable()->comment = "Target price";
            $table->string('target_price_prcnt', 60)->nullable()->comment = "Target price Percentage";
            $table->text('remarks')->nullable();
            $table->string('file', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_quotations');
    }
}
