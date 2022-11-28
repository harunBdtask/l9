<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortTrimsBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_trims_booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('short_booking_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('nominated_supplier_id')->nullable();

            $table->string('budget_unique_id', 30);
            $table->string('style_name', 30);
            $table->string('po_no');
            $table->string('item_name');
            $table->string('item_description')->nullable();

            $table->decimal('total_qty');

            $table->string('cons_uom_value')->nullable();
            $table->unsignedBigInteger('cons_uom_id')->nullable();

            $table->decimal('current_work_order_qty', 10, 4)->default(0);

            $table->decimal('total_amount', 10, 4)->default(0);
            $table->decimal('balance_amount', 10, 4)->default(0);
            $table->decimal('balance_qty')->default(0);
            $table->integer('sensitivity')->nullable();
            $table->decimal('work_order_qty')->default(0);
            $table->decimal('work_order_rate', 10, 4)->default(0);
            $table->decimal('work_order_amount', 10, 4)->default(0);
            $table->json('breakdown')->nullable();
            $table->json('details')->nullable();
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
        Schema::dropIfExists('short_trims_booking_details');
    }
}
