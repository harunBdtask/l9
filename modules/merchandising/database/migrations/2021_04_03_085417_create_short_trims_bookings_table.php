<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortTrimsBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_trims_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedTinyInteger('source');
            $table->unsignedTinyInteger('trims_type')->nullable();
            $table->unsignedTinyInteger('ready_to_approve')->default(1);
            $table->unsignedTinyInteger('pay_mode');
            $table->unsignedTinyInteger('material_source')->nullable();
            $table->unsignedTinyInteger('level')->nullable();
            $table->unsignedTinyInteger('booking_basis')->nullable();

            $table->string('location')->nullable();
            $table->string('remarks')->nullable();
            $table->string('attention')->nullable();
            $table->string('delivery_to')->nullable();
            $table->string('currency', 20)->nullable();

            $table->float('exchange_rate')->nullable();

            $table->date('booking_date');
            $table->date('delivery_date')->nullable();
            $table->json('terms_condition')->nullable();
            $table->json('details')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('short_trims_bookings');
    }
}
