<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsInSubDyeingTumblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_tumbles', function (Blueprint $table) {
            $table->dateTime('streaming_date')->nullable()->change();
            $table->dateTime('dry_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_tumbles', function (Blueprint $table) {
            $table->date('streaming_date')->nullable()->change();
            $table->date('dry_date')->nullable()->change();
        });
    }
}
