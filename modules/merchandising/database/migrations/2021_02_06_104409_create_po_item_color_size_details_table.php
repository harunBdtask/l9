<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoItemColorSizeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_item_color_size_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('garments_item_id');
            $table->text('colors')->nullable();
            $table->text('sizes')->nullable();
            $table->text('ratio_matrix')->nullable();
            $table->text('quantity_matrix')->nullable();
            $table->string('quantity')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('po_item_color_size_details');
    }
}
