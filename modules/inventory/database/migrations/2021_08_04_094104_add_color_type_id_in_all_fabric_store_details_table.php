<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorTypeIdInAllFabricStoreDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_receive_details', function (Blueprint $table) {
            $table->unsignedInteger('color_type_id')->nullable();
        });

        Schema::table('fabric_issue_details', function (Blueprint $table) {
            $table->unsignedInteger('color_type_id')->nullable();
        });

        Schema::table('fabric_receive_return_details', function (Blueprint $table) {
            $table->unsignedInteger('color_type_id')->nullable();
        });

        Schema::table('fabric_issue_return_details', function (Blueprint $table) {
            $table->unsignedInteger('color_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_receive_details', function (Blueprint $table) {
            $table->dropColumn('color_type_id');
        });

        Schema::table('fabric_issue_details', function (Blueprint $table) {
            $table->dropColumn('color_type_id');
        });

        Schema::table('fabric_receive_return_details', function (Blueprint $table) {
            $table->dropColumn('color_type_id');
        });

        Schema::table('fabric_issue_return_details', function (Blueprint $table) {
            $table->dropColumn('color_type_id');
        });
    }
}
