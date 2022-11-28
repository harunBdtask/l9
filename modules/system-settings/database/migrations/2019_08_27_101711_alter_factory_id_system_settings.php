<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFactoryIdSystemSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->change();
        });
        Schema::table('sizes', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->change();
        });
        Schema::table('yarn_compositions', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->change();
        });
        Schema::table('yarn_counts', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->change();
        });
        Schema::table('unit_of_measurements', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->change();
        });
        Schema::table('product_cateories', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->change();
        });
        Schema::table('product_departments', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->change();
        });
//        Schema::table('fabric_composition', function (Blueprint $table) {
//            $table->unsignedInteger('factory_id')->nullable()->change();
//        });
        Schema::table('color_types', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
