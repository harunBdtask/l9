<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGreyStoreReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_receive_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('sub_grey_store_receive_id');
            $table->unsignedBigInteger('supplier_id')->comment('party_type=Dyeing/Finishing Supplier');
            $table->unsignedBigInteger('sub_textile_order_id')->nullable();
            $table->unsignedBigInteger('sub_textile_order_detail_id')->nullable();
            $table->unsignedBigInteger('sub_grey_store_id');
            $table->string('challan_no');
            $table->date('challan_date')->nullable();
            $table->unsignedBigInteger('fabric_composition_id')->nullable()->comment('new_fabric_compositions table id');
            $table->unsignedBigInteger('fabric_type_id')->nullable()->comment('composition_types table id');
            $table->unsignedBigInteger('color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedBigInteger('color_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->tinyInteger('dia_type_id')->nullable()->comment('1=Open,2=Tubular,3=Needle Open');
            $table->string('gsm')->nullable();
            $table->text('fabric_description')->nullable()->comment('fabric_composition + fabric_type +color + ld_no + color_type + finish_dia + dia_type + gsm');
            $table->json('yarn_details')->nullable();
            $table->string('grey_required_qty')->nullable();
            $table->unsignedBigInteger('unit_of_measurement_id')->nullable();
            $table->string('total_roll')->nullable();
            $table->string('receive_qty')->nullable();
            $table->string('return_roll')->nullable();
            $table->string('receive_return_qty')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('sub_grey_store_receive_details');
    }
}
