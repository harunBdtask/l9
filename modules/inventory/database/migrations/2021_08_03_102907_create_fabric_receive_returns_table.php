<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricReceiveReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_receive_returns', function (Blueprint $table) {
            $table->id();
            $table->string('receive_return_no', 20)->nullable();
            $table->unsignedInteger('factory_id');
            $table->date('return_date');
            $table->string('mrr_no')->nullable();
            $table->unsignedInteger('returned_to_id');
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
        Schema::dropIfExists('fabric_receive_returns');
    }
}
