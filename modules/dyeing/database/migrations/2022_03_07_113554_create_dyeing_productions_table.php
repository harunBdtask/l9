<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDyeingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyeing_productions', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('dyeing_order_id');
            $table->string('dyeing_order_no');
            $table->unsignedBigInteger('dyeing_batch_id')->nullable();
            $table->string('dyeing_batch_no')->nullable();
            $table->unsignedBigInteger('dyeing_unit_id')->nullable();
            $table->date('production_date');
            $table->unsignedBigInteger('machine_id');
            $table->date('loading_date')->nullable();
            $table->date('unloading_date')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
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
        Schema::dropIfExists('dyeing_productions');
    }
}
