<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartsColorSizeColumnInBudget extends Migration
{
    public function up()
    {
        Schema::table('budget_direct_fabric_components', function (Blueprint $table) {
            $table->unsignedInteger('finish_fab_parts');
            $table->unsignedInteger('finish_fab_color');
            $table->string('finish_fab_size');
        });

        Schema::table('budget_yarn_components', function (Blueprint $table) {
            $table->unsignedInteger('yarn_garments_parts');
            $table->unsignedInteger('yarn_garments_color');
            $table->unsignedInteger('percent_share');
            $table->string('yarn_garments_size');
        });

        Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
            $table->unsignedInteger('gray_fabric_body_part');
            $table->unsignedInteger('gray_fabric_yarn_color');
            $table->string('gray_fabric_garments_size');
        });
    }

    public function down()
    {
        //
    }
}
