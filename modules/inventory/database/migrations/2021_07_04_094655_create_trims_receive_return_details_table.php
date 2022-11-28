<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsReceiveReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_receive_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('trims_receive_return_id');
            $table->string('order_uniq_id', 30)->nullable();
            $table->date('ship_date')->nullable();
            $table->string('style_name', 30)->nullable();
            $table->json('po_no')->nullable();
            $table->string('brand_sup_ref')->nullable();
            $table->unsignedInteger('item_id');
            $table->string('item_description')->nullable();
            $table->json('gmts_sizes')->nullable();
            $table->string('item_color')->nullable();
            $table->string('item_size')->nullable();
            $table->unsignedInteger('uom_id')->nullable();
            $table->string('return_qty', 20)->nullable();
            $table->string('rate', 20)->nullable()->comment('AVG from - receives');
            $table->string('amount', 20)->nullable();
            $table->string('floor', 30)->nullable();
            $table->string('room', 30)->nullable();
            $table->string('rack', 30)->nullable();
            $table->string('shelf', 30)->nullable();
            $table->string('bin', 30)->nullable();
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
        Schema::dropIfExists('trims_receive_return_details');
    }
}
