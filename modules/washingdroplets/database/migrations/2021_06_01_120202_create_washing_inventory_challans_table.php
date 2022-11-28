<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWashingInventoryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('washing_inventory_challans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('washing_challan_no', 25);
            $table->unsignedInteger('print_wash_factory_id');
            $table->tinyInteger('bag')->nullable();
            $table->unsignedInteger('factory_id');
            $table->tinyInteger('security_staus')->nullable()->comment('1=send,2=cancel,3=hold');
            $table->timestamps();
            $table->softDeletes();

            $table->index('washing_challan_no');
            $table->index('print_wash_factory_id');
            $table->index('factory_id');

            $table->foreign('print_wash_factory_id')->references('id')->on('print_factories')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('washing_inventory_challans');
    }
}
