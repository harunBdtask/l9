<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDryersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dryers', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id');
            $table->tinyInteger('entry_basis')->comment('1->batch 2->order');
            $table->unsignedBigInteger('textile_order_id')->nullable();
            $table->string('textile_order_no')->nullable();
            $table->unsignedBigInteger('dyeing_batch_id')->nullable();
            $table->string('dyeing_batch_no')->nullable();
            $table->unsignedBigInteger('dyeing_unit_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->date('production_date');
            $table->dateTime('loading_date')->nullable();
            $table->dateTime('unloading_date')->nullable();
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
        Schema::dropIfExists('dryers');
    }
}
