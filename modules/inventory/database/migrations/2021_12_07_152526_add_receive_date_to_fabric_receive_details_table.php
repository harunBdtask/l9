<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiveDateToFabricReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_receive_details', function (Blueprint $table) {
            $table->date('receive_date')->nullable()->after('receive_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_receive_details', function (Blueprint $table) {
            $table->dropColumn('receive_date');
        });
    }
}
