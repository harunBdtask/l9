<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricCostDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_cost_details', function (Blueprint $table) {
            $table->id();
            $table->string("quotation_id");
            $table->decimal("costing_multiplier");
            $table->unsignedBigInteger("garment_item_id");
            $table->unsignedBigInteger("body_part_id")->nullable();
            $table->unsignedBigInteger("fabric_nature_id")->nullable();
            $table->unsignedBigInteger("color_type_id")->nullable();
            $table->unsignedBigInteger("fabric_composition_id")->nullable();
            $table->unsignedBigInteger("fabric_source")->nullable()->comment("1=Production,2=Purchase,3=Buyer Supplier,4=Stock");
            $table->unsignedBigInteger("supplier_id")->nullable();
            $table->unsignedBigInteger("dia_type")->nullable()->comment("1=Open,2=Tabular,3=Middle Open");
            $table->decimal("gsm")->nullable();
            $table->boolean("status")->nullable();
            $table->unsignedBigInteger("consumption_basis")->nullable()->comment("1=Cad Basis,2=Measurement Basis,3=Marker Basis");
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('fabric_cost_details');
    }
}
