<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderUniqIdInTrimsReceiveDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_receive_details', function (Blueprint $table) {
            $table->string('order_uniq_id')->after('uniq_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_receive_details', function (Blueprint $table) {
            $table->dropColumn('order_uniq_id');
        });
    }
}
