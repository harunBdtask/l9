<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLoadingTimeColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_productions', function (Blueprint $table) {
            $table->dateTime('loading_date')->nullable()->change();
            $table->dateTime('unloading_date')->nullable()->change();
        });

        Schema::table('sub_dyeing_finishing_productions', function (Blueprint $table) {
            $table->dateTime('loading_date')->nullable()->change();
            $table->dateTime('unloading_date')->nullable()->change();
        });

        Schema::table('sub_dryers', function (Blueprint $table) {
            $table->dateTime('loading_date')->nullable()->change();
            $table->dateTime('unloading_date')->nullable()->change();
        });

        Schema::table('sub_slittings', function (Blueprint $table) {
            $table->dateTime('loading_date')->nullable()->change();
            $table->dateTime('unloading_date')->nullable()->change();
        });

        Schema::table('sub_compactors', function (Blueprint $table) {
            $table->dateTime('loading_date')->nullable()->change();
            $table->dateTime('unloading_date')->nullable()->change();
        });

        Schema::table('sub_dyeing_stenterings', function (Blueprint $table) {
            $table->dateTime('loading_date')->nullable()->change();
            $table->dateTime('unloading_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_productions', function (Blueprint $table) {
            $table->date('loading_date')->nullable()->change();
            $table->date('unloading_date')->nullable()->change();
        });

        Schema::table('sub_dyeing_finishing_productions', function (Blueprint $table) {
            $table->date('loading_date')->nullable()->change();
            $table->date('unloading_date')->nullable()->change();
        });

        Schema::table('sub_dryers', function (Blueprint $table) {
            $table->date('loading_date')->nullable()->change();
            $table->date('unloading_date')->nullable()->change();
        });

        Schema::table('sub_slittings', function (Blueprint $table) {
            $table->date('loading_date')->nullable()->change();
            $table->date('unloading_date')->nullable()->change();
        });

        Schema::table('sub_compactors', function (Blueprint $table) {
            $table->date('loading_date')->nullable()->change();
            $table->date('unloading_date')->nullable()->change();
        });

        Schema::table('sub_dyeing_stenterings', function (Blueprint $table) {
            $table->date('loading_date')->nullable()->change();
            $table->date('unloading_date')->nullable()->change();
        });
    }
}
