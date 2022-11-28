<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PurchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('factory_id');
            $table->string('order_status')->nullable()->comment('1=confirm, 2=projection');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->string('po_no');
            $table->string('po_receive_date')->nullable();
            $table->string('po_quantity')->nullable();
            $table->string('ex_factory_date')->nullable();
            $table->string('lead_time')->nullable();
            $table->string('avg_rate_pc_set')->nullable();
            $table->string('carton_info')->nullable();
            $table->string('internal_ref_no')->nullable();
            $table->string('comm_file_no')->nullable();
            $table->string('packing_ratio')->nullable()->comment('1= Solid Color Solid Size,2= Solid Color Asort Size,3= Asort Color Solid Size,4= Asort Color Asort Size');
            $table->string('status')->nullable();
            $table->string('customer')->nullable();
            $table->string('league')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('country_code')->nullable();
            $table->string('area')->nullable();
            $table->string('area_code')->nullable();
            $table->string('cut_off_date')->nullable();
            $table->string('cut_off')->nullable();
            $table->string('country_ship_date')->nullable();
            $table->string('pack_type')->nullable();
            $table->string('pcs_per_pack')->nullable();
            $table->string('matrix_type')->nullable()->comment('1=Garments with Full Quantity,2=Packing Ratio with Garments Quantity');
            $table->string('qty_copy_status')->nullable()->comment('1=ACAS, 2=SCAS, 3=ACSS');
            $table->string('ex_cut_percent_copy_status')->nullable()->comment('1=Yes,2=No');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::drop('purchase_orders');
    }
}
