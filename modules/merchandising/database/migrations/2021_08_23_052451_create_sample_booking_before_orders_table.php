<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleBookingBeforeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_booking_before_orders', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no', 25);
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('supplier_id');
            $table->date('booking_date');
            $table->date('delivery_date');
            $table->unsignedInteger('currency_id')->nullable();
            $table->unsignedTinyInteger('pay_mode')->nullable();
            $table->unsignedTinyInteger('fabric_source')->nullable();
            $table->string('exchange_rate', 20)->nullable();
            $table->unsignedInteger('team_leader_id')->nullable();
            $table->unsignedInteger('dealing_merchant_id')->nullable();
            $table->string('internal_ref')->nullable();
            $table->string('attention')->nullable();
            $table->unsignedTinyInteger('is_short')->nullable();
            $table->unsignedTinyInteger('ready_to_approve')->nullable();
            $table->unsignedInteger('fabric_nature_id')->nullable();
            $table->string('style_name')->nullable();
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
        Schema::dropIfExists('sample_booking_before_orders');
    }
}
