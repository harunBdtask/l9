<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubTextileOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_textile_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_uid')->nullable()->comment("Auto Generated UID");
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('order_no')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('repeat_order_no')->nullable();
            $table->text('description')->nullable();
            $table->string('revised_no')->nullable();
            $table->date('receive_date')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->tinyInteger('payment_basis')->default(1)->comment("1=Credit,2=At Sight,3=Bill Recipe");
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('sub_textile_orders');
    }
}
