<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricVirtualStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_virtual_stock', function (Blueprint $table) {
            $table->id();
            $table->string('composition');
            $table->string('construction');
            $table->string('gsm');
            $table->string('gmt_color');
            $table->string('item_color');
            $table->string('color_type');
            $table->string('dia');
            $table->string('stock');

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('fabric_virtual_stock');
    }
}
