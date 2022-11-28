<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wip_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('assign_factory_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->string('style');
            $table->string('order_qty')->nullable();
            $table->date('po_received_date')->nullable();
            $table->date('po_issued_to_fty')->nullable();
            $table->date('fabric_pi_recieved_date')->nullable();
            $table->date('sc_issue_date')->nullable();
            $table->date('revised_sc_issue_date')->nullable();
            $table->json('color_breakdown_as_per_po')->nullable();
            $table->json('customer_wise_po')->nullable();
            $table->date('bulk_tp_received_date')->nullable();
            $table->date('pcd')->nullable();
            $table->date('wip_date')->nullable();
            $table->date('po_delivery_date')->nullable();
            $table->string('final_costing_approved')->nullable();
            $table->date('costing_yy')->nullable();
            $table->date('packing_info_upc')->nullable();
            $table->date('ship_due_date')->nullable();
            $table->string('image')->nullable();
            $table->string('garments_item')->nullable();
            $table->json('fabric_booking_details')->nullable();
            $table->json('trims_booking_details')->nullable();
            $table->json('sample_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wip_reports');
    }
}
