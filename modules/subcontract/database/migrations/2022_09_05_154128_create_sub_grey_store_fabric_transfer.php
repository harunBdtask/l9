<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGreyStoreFabricTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_fabric_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_uid')->nullable();
            $table->string('criteria');
            $table->string('from_company');
            $table->string('to_company')->nullable();
            $table->string('transfer_date');
            $table->string('transfer_type');
            $table->string('challan_no');
            $table->string('ready_to_approve')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('sub_grey_store_fabric_transfers');
    }
}
