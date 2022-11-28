<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintReceiveInventoryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_receive_inventory_challans', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('type')->default(0)->comment('0 = challan, 1 = tag');
            $table->string('challan_no', 25);
            $table->smallInteger('operation_name')->nullable()->comment('1=print,2=embroidery');
            $table->unsignedInteger('table_id')->nullable();
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('print_receive_inventory_challans');
    }
}
