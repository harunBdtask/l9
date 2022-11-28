<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStorageLocationColumnToStorageLocationIdToDyesChemicalsReceive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemicals_receive', function (Blueprint $table) {
            $table->renameColumn('storage_location','storage_location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyes_chemicals_receive', function (Blueprint $table) {
            $table->renameColumn('storage_location_id','storage_location');
        });
    }
}
