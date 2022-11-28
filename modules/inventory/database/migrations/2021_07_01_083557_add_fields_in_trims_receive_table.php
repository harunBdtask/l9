<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInTrimsReceiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_receives', function (Blueprint $table) {
            $table->enum('booking_type', ['main', 'short'])->nullable();
            $table->unsignedInteger('buyer_id')->nullable();

        });

        Schema::table('trims_receive_details', function (Blueprint $table) {
            $table->string('uniq_id', 30)
                ->nullable()
                ->after('id')
                ->comment('Auto Generated ID');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_receives', function (Blueprint $table) {
            $table->dropColumn('booking_type');
            $table->dropColumn('buyer_id');
        });

        Schema::table('trims_receive_details', function (Blueprint $table) {
            $table->dropColumn('uniq_id');
        });
    }
}
