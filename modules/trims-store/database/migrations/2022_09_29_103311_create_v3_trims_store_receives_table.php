<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateV3TrimsStoreReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v3_trims_store_receives', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('store_id');
            $table->tinyInteger('receive_basis_id');
            $table->string('challan_no');
            $table->date('receive_date');
            $table->string('pi_numbers')->nullable();
            $table->date('pi_receive_date')->nullable();
            $table->string('lc_no')->nullable();
            $table->date('lc_receive_date')->nullable();
            $table->tinyInteger('pay_mode_id')->nullable();
            $table->tinyInteger('ready_to_approve')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('v3_trims_store_receives');
    }
}
