<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToPoFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_files', function (Blueprint $table) {
            $table->index('buyer_id');
            $table->index('po_no');
            $table->index('style');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_files', function (Blueprint $table) {
            $table->dropIndex(['buyer_id']);
            $table->dropIndex(['po_no']);
            $table->dropIndex(['style']);
        });
    }
}
