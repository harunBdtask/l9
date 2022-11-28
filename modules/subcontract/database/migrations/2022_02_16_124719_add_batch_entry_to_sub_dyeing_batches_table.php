<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchEntryToSubDyeingBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_batches', function (Blueprint $table) {
            $table->tinyInteger('batch_entry')->nullable()->after('batch_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_batches', function (Blueprint $table) {
            $table->dropColumn('batch_entry');
        });
    }
}
