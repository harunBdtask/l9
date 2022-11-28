<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinishingFloorAndTableIdInPoliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('polies', function (Blueprint $table) {
            $table->unsignedBigInteger('finishing_floor_id')->nullable()->after('production_date');
            $table->unsignedBigInteger('finishing_table_id')->nullable()->after('finishing_floor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('polies', function (Blueprint $table) {
            $table->dropColumn([
                'finishing_floor_id',
                'finishing_table_id',
            ]);
        });
    }
}
