<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexingInSewingoutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sewingoutputs', function (Blueprint $table) {
            $table->index('output_challan_no');
            $table->index('status');
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
        Schema::table('sewingoutputs', function (Blueprint $table) {
            $table->dropIndex('sewingoutputs_output_challan_no_index');
            $table->dropIndex('sewingoutputs_status_index');
            $table->dropIndex('sewingoutputs_created_at_index');
        });
    }
}
