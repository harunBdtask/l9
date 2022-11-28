<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiveDateToTrimsStoreReceives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_receives', function (Blueprint $table) {
            $table->string('receive_date')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_store_receives', function (Blueprint $table) {
            $table->dropColumn('receive_date');
        });
    }
}

