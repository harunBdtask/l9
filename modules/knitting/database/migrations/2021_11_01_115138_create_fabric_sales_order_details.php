<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricSalesOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_sales_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fabric_sales_order_id');
            $table->unsignedInteger('garments_item_id');
            $table->unsignedInteger('body_part_id');
            $table->unsignedInteger('color_type_id');
            $table->text('fabric_description');
            $table->string('fabric_gsm');
            $table->string('fabric_dia');

            $table->unsignedInteger('dia_type_id')->nullable();
            $table->unsignedInteger('gmt_color_id')->nullable();//new
            $table->string('gmt_color')->nullable();//new
            $table->unsignedInteger('item_color_id')->nullable();//new
            $table->string('item_color')->nullable();//new
            $table->string('color_range')->nullable();
            $table->unsignedInteger('color_range_id')->nullable();//new
            $table->unsignedInteger('cons_uom')->nullable();
            $table->string('booking_qty')->nullable();

            $table->string('average_price', 30);//change data type
            $table->string('amount', 30);// change data type

            $table->unsignedInteger('prog_uom')->nullable();
            $table->string('finish_qty')->nullable();
            $table->string('process_loss')->nullable();
            $table->string('gray_qty')->nullable();
            $table->unsignedInteger('process_id')->nullable();//new
            $table->unsignedInteger('fabric_nature_id')->nullable();//new
            $table->string('fabric_nature')->nullable();//new
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('fabric_sales_order_details');
    }
}
