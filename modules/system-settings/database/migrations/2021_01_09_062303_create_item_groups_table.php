<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id')->nullable();
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('factory_id');
            $table->string('item_group')->nullable();
            $table->string('group_code')->nullable();
            $table->string('trims_type')->nullable();
            $table->unsignedInteger('order_uom')->nullable();
            $table->unsignedInteger('cons_uom')->nullable();
            $table->string('conv_factor')->nullable();
            $table->string('fancy_item')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('item_groups');
    }
}
