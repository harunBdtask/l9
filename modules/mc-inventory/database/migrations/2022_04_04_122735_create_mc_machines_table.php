<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMcMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barcode')->nullable();
            $table->unsignedBigInteger('barcode_generation_id')->nullable();
            $table->unsignedBigInteger('factory_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('model_no')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('sub_type_id')->nullable();
            $table->tinyInteger('origin')->nullable();
            $table->string('serial_no')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->text('description')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('last_maintenance')->nullable();
            $table->integer('tenor')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('mc_machines');
    }
}
