<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_productions', function (Blueprint $table) {
            $table->id();
            $table->string('production_uid')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('order_id');
            $table->string('order_no');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->date('production_date');
            $table->date('loading_date')->nullable();
            $table->date('unloading_date')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
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
        Schema::dropIfExists('sub_dyeing_productions');
    }
}
