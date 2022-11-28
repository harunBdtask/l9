<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIndexInCuttingInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cutting_inventories', function (Blueprint $table) {
            $table->index('status');
            $table->index('print_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cutting_inventories', function (Blueprint $table) {
            $table->dropIndex('cutting_inventories_status_index');
            $table->dropIndex('cutting_inventories_print_status_index');
            $table->dropIndex('cutting_inventories_created_at_index');
        });
    }
}
