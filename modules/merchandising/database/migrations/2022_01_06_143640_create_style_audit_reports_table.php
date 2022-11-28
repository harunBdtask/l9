<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStyleAuditReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_audit_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('style_id');
            $table->string('order_qty')->default(0)->nullable();
            $table->string('order_value')->default(0)->nullable();
            $table->json('fabric_req_qty')->nullable();
            $table->json('yarn_issue_qty')->nullable();
            $table->string('yarn_issue_value')->default(0)->nullable();
            $table->string('fabric_cost_value')->default(0)->nullable();
            $table->string('trims_cost_Value')->default(0)->nullable();
            $table->string('others_cost')->default(0)->nullable();
            $table->string('budget_value')->default(0)->nullable();
            $table->json('fabric_booked_qty')->nullable();
            $table->string('fabric_booked_value')->default(0)->nullable();
            $table->string('knitting_qty')->default(0)->nullable();
            $table->string('knitting_value')->default(0)->nullable();
            $table->string('dyeing_qty')->default(0)->nullable();
            $table->string('dyeing_value')->default(0)->nullable();
            $table->json('finish_fab_qty')->nullable();
            $table->string('finish_fab_value')->default(0)->nullable();
            $table->string('cutting_qty')->default(0)->nullable();
            $table->string('cutting_value')->default(0)->nullable();
            $table->string('print_sent_qty')->default(0)->nullable();
            $table->string('print_sent_value')->default(0)->nullable();
            $table->string('print_receive_qty')->default(0)->nullable();
            $table->string('print_receive_value')->default(0)->nullable();
            $table->string('embr_sent_qty')->default(0)->nullable();
            $table->string('embr_sent_value')->default(0)->nullable();
            $table->string('embr_receive_qty')->default(0)->nullable();
            $table->string('embr_receive_value')->default(0)->nullable();
            $table->string('input_qty')->default(0)->nullable();
            $table->string('input_value')->default(0)->nullable();
            $table->string('sewing_qty')->default(0)->nullable();
            $table->string('sewing_value')->default(0)->nullable();
            $table->string('iron_qty')->default(0)->nullable();
            $table->string('iron_value')->default(0)->nullable();
            $table->string('poly_qty')->default(0)->nullable();
            $table->string('poly_value')->default(0)->nullable();
            $table->string('packing_qty')->default(0)->nullable();
            $table->string('packing_value')->default(0)->nullable();
            $table->string('shipment_qty')->default(0)->nullable();
            $table->string('shipment_value')->default(0)->nullable();

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
        Schema::dropIfExists('style_audit_reports');
    }
}
