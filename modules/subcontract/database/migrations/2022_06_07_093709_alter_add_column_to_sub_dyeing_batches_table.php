<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddColumnToSubDyeingBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_batches', function (Blueprint $table) {
            $table->string('buyer_rate')->nullable()->default(0)->after('total_machine_capacity');
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
            $table->dropColumn(['buyer_rate']);
        });
    }
}
