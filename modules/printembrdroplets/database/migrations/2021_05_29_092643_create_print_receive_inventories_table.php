<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintReceiveInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_receive_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('challan_no', 25);
            $table->unsignedInteger('bundle_card_id');
            $table->unsignedInteger('factory_id');
            $table->tinyInteger('status')->default(0);
            $table->smallInteger('production_status')->nullable();
            $table->string('production_challan_no', 25)->nullable();
            $table->unsignedInteger('created_by')->nullable();
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
        Schema::dropIfExists('print_receive_inventories');
    }
}
