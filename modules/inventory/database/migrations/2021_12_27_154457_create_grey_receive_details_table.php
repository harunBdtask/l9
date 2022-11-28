<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGreyReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grey_receive_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('grey_receive_id');
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('knitting_program_id')->nullable();
            $table->unsignedInteger('plan_info_id')->nullable();
            $table->unsignedInteger('knitting_program_roll_id')->nullable();
            $table->json('yarn_composition_id')->nullable();
            $table->json('yarn_count_id')->nullable();
            $table->string('factory_name')->nullable();
            $table->string('book_company')->nullable();
            $table->string('knitting_source_value')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('style_name')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('po_no')->nullable();
            $table->string('booking_no')->nullable();
            $table->string('body_part')->nullable();
            $table->string('color_type')->nullable();
            $table->string('fabric_description')->nullable();
            $table->string('item_color')->nullable();
            $table->string('program_no')->nullable();
            $table->string('production_qty')->nullable();
            $table->string('pcs_production_qty')->nullable();
            $table->string('scanable_barcode')->nullable();
            $table->json('yarn_lot')->nullable();
            $table->json('yarn_count_value')->nullable();
            $table->json('yarn_composition_value')->nullable();
            $table->json('yarn_brand')->nullable();
            $table->integer('delivery_status')->default(0);
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
        Schema::dropIfExists('grey_receive_details');
    }
}
