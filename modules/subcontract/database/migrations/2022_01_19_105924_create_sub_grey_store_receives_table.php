<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGreyStoreReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->tinyInteger('receive_basis')->comment('1=Independent,2=Order Basis');
            $table->unsignedBigInteger('sub_textile_order_id')->nullable();
            $table->unsignedBigInteger('sub_grey_store_id');
            $table->string('challan_no');
            $table->date('challan_date')->nullable();
            $table->text('required_operations')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('sub_grey_store_receives');
    }
}
