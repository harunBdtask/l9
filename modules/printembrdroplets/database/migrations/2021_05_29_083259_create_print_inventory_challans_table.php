<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintInventoryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_inventory_challans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('challan_no', 25);
            $table->boolean('status')->default(0);
            $table->tinyInteger('bag')->nullable();
            $table->tinyInteger('operation_name')->default(1)->comment('1=print,2=embroidery');
            $table->unsignedInteger('part_id');
            $table->integer('send_total_qty')->default(0);
            $table->unsignedInteger('print_factory_id');
            $table->tinyInteger('security_status')->default(0)->comment('	1=send,2=hold,3=cancel');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('challan_no');
            $table->index('print_factory_id');
            $table->index('factory_id');

            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
            $table->foreign('print_factory_id')->references('id')->on('print_factories')->onDelete('cascade');
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
        Schema::dropIfExists('print_inventory_challans');
    }
}
