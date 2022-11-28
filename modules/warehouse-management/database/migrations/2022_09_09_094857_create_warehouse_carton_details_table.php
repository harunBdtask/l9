<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseCartonDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_carton_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('warehouse_carton_id')->index();
            $table->unsignedInteger('color_id')->nullable()->index();
            $table->unsignedInteger('size_id')->nullable()->index();
            $table->string('quantity')->nullable();
            $table->unsignedInteger('factory_id')->index();
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
        Schema::dropIfExists('warehouse_carton_details');
    }
}
