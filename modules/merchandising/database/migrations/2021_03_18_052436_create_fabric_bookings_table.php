<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedTinyInteger('fabric_source')
                ->comment('1 => Production, 2 => Purchase, 3 => Buyer, 4 => Supplier Stock');
            $table->unsignedInteger('factory_id')->nullable();
            $table->date('booking_date');
            $table->date('delivery_date')->nullable();
            $table->unsignedTinyInteger('pay_mode')->nullable()
                ->comment('1 => Credit, 2 => Import, 3 => In House, 4 => Within Group');
            $table->unsignedTinyInteger('source')->nullable()
                ->comment('1 => Abroad, 2 => Epz, 3 => Non-Epz');
            $table->unsignedInteger('currency_id')->nullable();
            $table->float('exchange_rate')->nullable();
            $table->unsignedTinyInteger('ready_to_approve')->default(1);
            $table->string('internal_ref_no')->nullable();
            $table->string('attention')->nullable();
            $table->unsignedTinyInteger('level')->nullable()->comment('1 => Job Label, 2 => Po Label');
            $table->string('booking_percent')->nullable();
            $table->string('file_no')->nullable();
            $table->string('fabric_composition')->nullable();
            $table->string('remarks')->nullable();
            $table->json('terms_condition')->nullable();
            $table->string('process_loss')->nullable();
            $table->string('collar_cuff_info')->nullable();
            $table->unsignedTinyInteger('ready_to_approved')->nullable()
                ->comment('1 => Yes, 2 => No');
            $table->string('un_approve_request')->nullable();
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
        Schema::dropIfExists('fabric_bookings');
    }
}
