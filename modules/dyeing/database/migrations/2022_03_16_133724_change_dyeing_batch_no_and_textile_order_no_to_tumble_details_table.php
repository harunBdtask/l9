<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDyeingBatchNoAndTextileOrderNoToTumbleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tumble_details', function (Blueprint $table) {
            $table->string('dyeing_batch_no')->change()->after('dyeing_batch_id');
            $table->string('textile_order_no')->change()->after('textile_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tumble_details', function (Blueprint $table) {
            $table->unsignedInteger('dyeing_batch_no')->change()->after('dyeing_batch_id');
            $table->unsignedInteger('textile_order_no')->change()->after('textile_order_id');
        });
    }
}
