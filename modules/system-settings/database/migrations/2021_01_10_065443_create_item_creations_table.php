<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCreationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_creations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('item_group_id');
            $table->string('sub_group_code');
            $table->string('sub_group_name');
            $table->string('item_code');
            $table->string('item_description')->nullable();
            $table->string('item_size')->nullable();
            $table->string('re_order_label')->nullable();
            $table->string('min_label')->nullable();
            $table->string('max_label')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('item_creations');
    }
}
