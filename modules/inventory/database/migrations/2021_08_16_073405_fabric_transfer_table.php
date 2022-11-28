<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FabricTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_no', 20)->comment('Auto generated');
            $table->string('transfer_criteria', 30);
            $table->date('transfer_date');
            $table->unsignedInteger('factory_id');
            $table->string('location');
            $table->unsignedInteger('to_factory_id');
            $table->string('to_location');
            $table->string('challan_no', 40);
            $table->unsignedTinyInteger('created_by')->nullable();
            $table->unsignedTinyInteger('updated_by')->nullable();
            $table->unsignedTinyInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('fabric_transfers');
    }
}
