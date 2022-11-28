<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmbellishmentWorkOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('embellishment_work_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('embellishment_work_order_id');
            $table->string('budget_unique_id');
            $table->string('po_no');
            $table->string('style');
            $table->unsignedBigInteger('embellishment_id');
            $table->unsignedBigInteger('embellishment_type_id');
            $table->unsignedBigInteger('body_part_id');
            $table->decimal('total_qty', 8, 2)->nullable();
            $table->string('sensitivity')->nullable();
            $table->decimal('current_work_order_qty', 8, 2)->nullable();
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->decimal('balance_amount', 8, 2)->nullable();
            $table->decimal('work_order_qty', 8, 2)->nullable();
            $table->decimal('balance_qty', 8, 2)->nullable();
            $table->decimal('work_order_rate', 8, 2)->nullable();
            $table->decimal('work_order_amount', 8, 2)->nullable();
            $table->json('breakdown')->nullable();
            $table->json('details')->nullable();
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
        Schema::dropIfExists('embellishment_work_order_details');
    }
}
