<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceivableTypeAndTypeIdInFabricReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_receive_details', function (Blueprint $table) {
            $table->string('receivable_type', 30)->nullable()->after('unique_id');
            $table->bigInteger('receivable_id')->nullable()->after('receivable_type')->comment('receivable_id in fabric_receives table');
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
            $table->dropColumn(['receivable_type', 'receivable_id']);
        });
    }
}
