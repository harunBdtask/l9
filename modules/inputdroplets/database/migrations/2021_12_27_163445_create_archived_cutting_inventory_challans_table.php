<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivedCuttingInventoryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archived_cutting_inventory_challans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('challan_no', 25)->nullable();
            $table->boolean('status')->default(0);
            $table->unsignedInteger('line_id')->nullable();
            $table->string('type', 10);
            $table->boolean('print_status')->default(0);
            $table->date('input_date')->nullable();
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('factory_id');
            $table->string('total_rib_size')->nullable();
            $table->string('rib_comments')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('challan_no');
            $table->index('line_id');
            $table->index('input_date');
            $table->index('color_id');

            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('archived_cutting_inventory_challans');
    }
}
