<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHourColumnsInHourWiseFinishingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hour_wise_finishing_productions', function (Blueprint $table) {
            $table->string('hour_0')->default(null)->nullable()->change();
            $table->string('hour_1')->default(null)->nullable()->change();
            $table->string('hour_2')->default(null)->nullable()->change();
            $table->string('hour_3')->default(null)->nullable()->change();
            $table->string('hour_4')->default(null)->nullable()->change();
            $table->string('hour_5')->default(null)->nullable()->change();
            $table->string('hour_6')->default(null)->nullable()->change();
            $table->string('hour_7')->default(null)->nullable()->change();
            $table->string('hour_8')->default(null)->nullable()->change();
            $table->string('hour_9')->default(null)->nullable()->change();
            $table->string('hour_10')->default(null)->nullable()->change();
            $table->string('hour_11')->default(null)->nullable()->change();
            $table->string('hour_12')->default(null)->nullable()->change();
            $table->string('hour_13')->default(null)->nullable()->change();
            $table->string('hour_14')->default(null)->nullable()->change();
            $table->string('hour_15')->default(null)->nullable()->change();
            $table->string('hour_16')->default(null)->nullable()->change();
            $table->string('hour_17')->default(null)->nullable()->change();
            $table->string('hour_18')->default(null)->nullable()->change();
            $table->string('hour_19')->default(null)->nullable()->change();
            $table->string('hour_20')->default(null)->nullable()->change();
            $table->string('hour_21')->default(null)->nullable()->change();
            $table->string('hour_22')->default(null)->nullable()->change();
            $table->string('hour_23')->default(null)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hour_wise_finishing_productions', function (Blueprint $table) {
            $table->integer('hour_0')->default(0)->nullable()->change();
            $table->integer('hour_1')->default(0)->nullable()->change();
            $table->integer('hour_2')->default(0)->nullable()->change();
            $table->integer('hour_3')->default(0)->nullable()->change();
            $table->integer('hour_4')->default(0)->nullable()->change();
            $table->integer('hour_5')->default(0)->nullable()->change();
            $table->integer('hour_6')->default(0)->nullable()->change();
            $table->integer('hour_7')->default(0)->nullable()->change();
            $table->integer('hour_8')->default(0)->nullable()->change();
            $table->integer('hour_9')->default(0)->nullable()->change();
            $table->integer('hour_10')->default(0)->nullable()->change();
            $table->integer('hour_11')->default(0)->nullable()->change();
            $table->integer('hour_12')->default(0)->nullable()->change();
            $table->integer('hour_13')->default(0)->nullable()->change();
            $table->integer('hour_14')->default(0)->nullable()->change();
            $table->integer('hour_15')->default(0)->nullable()->change();
            $table->integer('hour_16')->default(0)->nullable()->change();
            $table->integer('hour_17')->default(0)->nullable()->change();
            $table->integer('hour_18')->default(0)->nullable()->change();
            $table->integer('hour_19')->default(0)->nullable()->change();
            $table->integer('hour_20')->default(0)->nullable()->change();
            $table->integer('hour_21')->default(0)->nullable()->change();
            $table->integer('hour_22')->default(0)->nullable()->change();
            $table->integer('hour_23')->default(0)->nullable()->change();
        });
    }
}
