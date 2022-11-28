<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGreyDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grey_delivery_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('grey_delivery_id');
            $table->unsignedInteger('grey_receive_details_id');
            $table->unsignedInteger('knitting_program_id');
            $table->unsignedInteger('plan_info_id');
            $table->unsignedInteger('knitting_program_roll_id');
            $table->string('challan_no');
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
        Schema::dropIfExists('grey_delivery_details');
    }
}
