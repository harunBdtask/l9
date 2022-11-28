<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToArchiveCuttingInventoryChallanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archived_cutting_inventory_challans', function (Blueprint $table) {
            $table->tinyInteger('cut_manager_approval_steps')->default(0);
            $table->tinyInteger('cut_manager_approval_status')->default(0)->comment('0=Not Approved,1=Approved');
            $table->unsignedInteger('cut_manager_approved_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archived_cutting_inventory_challans', function (Blueprint $table) {
            $table->dropIndex('cutting_inventories_status_index');
            $table->dropIndex('cutting_inventories_print_status_index');
            $table->dropIndex('cutting_inventories_created_at_index');
        });
    }
}
