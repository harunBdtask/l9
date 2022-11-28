<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStreamingDateAndDryDateToTumblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tumbles', function (Blueprint $table) {
            $table->dateTime('streaming_date')->change()->after('production_date');
            $table->dateTime('dry_date')->change()->after('shift_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tumbles', function (Blueprint $table) {
            $table->date('streaming_date')->change()->after('production_date');
            $table->date('dry_date')->change()->after('shift_id');
        });
    }
}
