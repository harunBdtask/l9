<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateYarnStoreNoOfBagToWeightPerConeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    protected function change($table){

    }

    public function up()
    {
        Schema::table('yarn_receive_details', function (Blueprint $table) {
            $table->string('no_of_bag',255)->nullable()->change();
            $table->string('weight_per_bag',255)->nullable()->change();
            $table->string('weight_per_cone',255)->nullable()->change();
            $table->string('no_of_loose_cone',255)->nullable()->change();
            $table->string('no_of_cone_per_bag',255)->nullable()->change();
        });

        Schema::table('yarn_issue_details', function (Blueprint $table) {
            $table->string('no_of_bag',255)->nullable()->change();
            $table->string('no_of_cone',255)->nullable()->change();
            $table->string('weight_per_bag',255)->nullable()->change();
            $table->string('weight_per_cone',255)->nullable()->change();
            $table->string('no_of_cone_per_bag',255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // just change data type no need to drop
    }
}
