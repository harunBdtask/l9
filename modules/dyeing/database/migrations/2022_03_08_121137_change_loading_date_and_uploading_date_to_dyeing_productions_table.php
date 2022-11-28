<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLoadingDateAndUploadingDateToDyeingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyeing_productions', function (Blueprint $table) {
            $table->dropColumn('machine_id');
            $table->dateTime('loading_date')->nullable()->change()->after('production_date');
            $table->dateTime('unloading_date')->nullable()->change()->after('loading_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyeing_productions', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id')->nullable()->after('production_date');
            $table->date('loading_date')->nullable()->change()->after('machine_id');
            $table->date('unloading_date')->nullable()->change()->after('loading_date');
        });
    }
}
