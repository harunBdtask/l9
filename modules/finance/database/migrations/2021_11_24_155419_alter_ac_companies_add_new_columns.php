<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAcCompaniesAddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_companies', function (Blueprint $table) {
            $table->string('group_name', 60)->nullable();
            $table->string('corporate_address', 60)->nullable();
            $table->string('factory_address', 60)->nullable();
            $table->string('tin', 60)->nullable();
            $table->string('country', 30)->nullable();
            $table->string('email', 50)->nullable();
            $table->dropColumn('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('ac_companies', function (Blueprint $table) {
//            $table->string('group_name', 60)->nullable();
//            $table->string('corporate_address', 60)->nullable();
//            $table->string('factory_address', 60)->nullable();
//            $table->string('tin', 60)->nullable();
//            $table->string('country', 30)->nullable();
//            $table->string('email', 50)->nullable();
//            $table->dropColumn('address');
//        });
        Schema::table('ac_companies', function (Blueprint $table) {
            $table->dropColumn('group_name');
            $table->dropColumn('corporate_address');
            $table->dropColumn('factory_address');
            $table->dropColumn('tin');
            $table->dropColumn('country');
            $table->dropColumn('email');
            $table->string('address', 60)->nullable();
        });
    }
}
