<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         *
        'floor',
        'room',
        'rack',
        'shelf',
        'bin'
        */
        Schema::create('trims_receive_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trims_receive_id');
            $table->date('ship_date')->nullable();
            $table->string('style_name')->nullable();
            $table->json('po_no')->nullable()->comment('Array');
            $table->string('ref_no')->nullable();
            $table->string('brand_sup_ref')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('item_description')->nullable();
            $table->json('gmts_sizes')->nullable()->comment('Array');
            $table->string('item_color', 30)->nullable();
            $table->string('item_size', 30)->nullable();
            $table->unsignedInteger('uom_id');

            $table->string('wo_pi_qty', 20)->nullable();
            $table->string('receive_qty', 20)->nullable();
            $table->string('rate', 20)->nullable();
            $table->string('amount', 20)->nullable();
            $table->string('reject_qty', 20)->nullable();

            $table->enum('payment_for_over_receive_qty', ['yes', 'no'])->nullable();

            $table->string('floor', 20)->nullable();
            $table->string('room', 20)->nullable();
            $table->string('rack', 20)->nullable();
            $table->string('shelf', 20)->nullable();
            $table->string('bin', 20)->nullable();

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
        Schema::dropIfExists('trims_receive_details');
    }
}
