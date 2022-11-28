<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubSlittingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_slittings', function (Blueprint $table) {
            $table->id();
            $table->string('sub_slitting_uid')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->tinyInteger('entry_basis')->comment('1->batch 2->order');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('order_no')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->unsignedBigInteger('dyeing_unit_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->date('production_date');
            $table->date('loading_date')->nullable();
            $table->date('unloading_date')->nullable();
            $table->string('machine_speed')->nullable();
            $table->string('over_feed')->nullable();
            $table->string('pressure')->nullable();
            $table->string('output_width')->nullable();
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
        Schema::dropIfExists('sub_slittings');
    }
}
