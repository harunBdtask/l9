<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('commercial_name', 50)->nullable();
            $table->unsignedInteger('product_category_id');
            $table->string('product_type');
            $table->string('standard_smv', 50)->nullable();
            $table->string('efficiency', 50)->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('garments_items');
    }
}
