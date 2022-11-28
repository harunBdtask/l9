<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnittingRollDeliveryChallanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knitting_roll_delivery_challan_details', function (Blueprint $table) {
            $table->id();
            $table->string('challan_no');
            $table->unsignedBigInteger('plan_info_id');
            $table->unsignedBigInteger('knitting_program_id');
            $table->unsignedBigInteger('knitting_program_roll_id');
            $table->tinyInteger('challan_status')->default(0)->comment("0=No,1=Yes");
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('knitting_roll_delivery_challan_details');
    }
}
