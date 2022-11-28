<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFabricBookingsTableAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_bookings', function (Blueprint $table) {
                $table->string('attachment_note')->nullable();
                $table->string('attachment')->nullable();
                $table->string('control')->nullable();
                $table->string('silicon_engyme_wash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_bookings', function (Blueprint $table) {
            $table->dropColumn('attachment_note');
            $table->dropColumn('attachment');
            $table->dropColumn('control');
            $table->dropColumn('silicon_engyme_wash');
        });
    }
}
