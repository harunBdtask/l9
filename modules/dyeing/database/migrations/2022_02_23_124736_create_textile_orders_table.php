<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextileOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('textile_orders', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('fabric_sales_order_id');
            $table->string('fabric_sales_order_no');
            $table->text('description')->nullable();
            $table->date('receive_date')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->tinyInteger('payment_basis')->nullable()
                ->comment('1 = Credit, 2 = At Sight, 3 = Bill Recipe');

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
        Schema::dropIfExists('textile_orders');
    }
}
