<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procure_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number');
            $table->unsignedInteger('requisition_id');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->date('po_date')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->tinyInteger('is_integrated')->default(0);
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
        Schema::dropIfExists('procure_purchase_orders');
    }
}
