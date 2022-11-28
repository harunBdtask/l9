<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricServiceBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_service_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no', 30);
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('supplier_id');
            $table->date('booking_date');
            $table->date('delivery_date')->nullable();
            $table->unsignedInteger('pay_mode');
            $table->unsignedInteger('source')->nullable();
            $table->unsignedInteger('currency');
            $table->decimal('exchange_rate', 10, 4)->nullable();
            $table->text('attention')->nullable();
            $table->tinyInteger('label')->comment('1=Style Wise, 2=PO Wise');
            $table->tinyInteger('ready_to_approve')->nullable();
            $table->text('unapproved_request')->nullable();
            $table->tinyInteger('is_approved')->default(0)->comment('1=Approved');
            $table->unsignedInteger('process');

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
        Schema::dropIfExists('fabric_service_bookings');
    }
}
