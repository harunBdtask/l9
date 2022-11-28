<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleTrimsBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_trims_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('supplier_id');

            $table->unsignedTinyInteger('source');
            $table->unsignedTinyInteger('ready_to_approve')->nullable();
            $table->unsignedTinyInteger('pay_mode');
            $table->unsignedTinyInteger('material_source')->nullable();
            $table->unsignedTinyInteger('booking_basis')->nullable();

            $table->string('location');
            $table->date('booking_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('attention')->nullable();
            $table->string('currency', 20)->nullable();
            $table->string('exchange_rate')->nullable();
            $table->string('delivery_to')->nullable();
            $table->string('remarks')->nullable();
            $table->text('terms_and_condition')->nullable();
            $table->string('unapprove_request')->nullable();

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
        Schema::dropIfExists('sample_trims_bookings');
    }
}
