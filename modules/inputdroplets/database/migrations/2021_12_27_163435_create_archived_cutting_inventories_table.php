<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivedCuttingInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archived_cutting_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('challan_no', 25);
            $table->unsignedInteger('bundle_card_id');
            $table->tinyInteger('status')->default(0);
            $table->boolean('print_status')->default(0);
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('challan_no');
            $table->index('bundle_card_id');

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
        Schema::dropIfExists('archived_cutting_inventories');
    }
}
