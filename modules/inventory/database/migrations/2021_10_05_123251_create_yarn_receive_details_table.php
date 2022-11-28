<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_receive_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('yarn_type_id');
            $table->unsignedInteger('yarn_count_id');
            $table->unsignedInteger('yarn_receive_id');
            $table->unsignedInteger('yarn_composition_id');
            $table->string('yarn_color')->nullable();
            $table->string('yarn_lot');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('supplier_id');
            $table->decimal('receive_qty', 10, 4);
            $table->string('yarn_brand');
            $table->unsignedInteger('uom_id')->nullable();
            $table->decimal('rate', 10, 4);
            $table->decimal('amount', 10, 4);
            $table->decimal('balance_qty', 10, 4)->nullable();
            $table->decimal('book_currency', 10, 4)->nullable();
            $table->integer('no_of_bag')->nullable();
            $table->integer('no_of_cone_per_bag')->nullable();
            $table->integer('no_of_loose_cone')->nullable();
            $table->integer('weight_per_bag')->nullable();
            $table->integer('weight_per_cone')->nullable();
            $table->string('product_code')->nullable();
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedInteger('rack_id')->nullable();
            $table->unsignedInteger('shelf_id')->nullable();
            $table->unsignedInteger('bin_id')->nullable();
            $table->text('remarks')->nullable();
            $table->decimal('over_receive_qty', 10, 4)->nullable();
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
        Schema::dropIfExists('yarn_receive_details');
    }
}
