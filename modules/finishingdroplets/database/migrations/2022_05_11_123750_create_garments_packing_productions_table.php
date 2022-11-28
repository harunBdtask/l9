<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsPackingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments_packing_productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->date('production_date');
            $table->string('packing_ratio');
            $table->json('carton_details')->nullable();
            $table->json('color_size_wise_qty_breakdown')->nullable();
            $table->json('colors')->nullable();
            $table->json('sizes')->nullable();
            $table->string('grand_total_cartons')->nullable();
            $table->string('grand_total_n_wt')->nullable();
            $table->string('grand_total_g_wt')->nullable();
            $table->string('grand_total_cbm')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('garments_packing_productions');
    }
}
