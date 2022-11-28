<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnsToYarnAllocationDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_allocation_details', function (Blueprint $table) {
            $table->unsignedBigInteger('yarn_count_id')->after('yarn_description')->nullable();
            $table->unsignedBigInteger('yarn_composition_id')->after('yarn_count_id')->nullable();
            $table->unsignedBigInteger('yarn_type_id')->after('yarn_composition_id')->nullable();
            $table->string('yarn_color')->after('yarn_type_id')->nullable();
            $table->string('yarn_brand')->after('yarn_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_allocation_details', function (Blueprint $table) {
            $table->dropColumn('yarn_count_id');
            $table->dropColumn('yarn_composition_id');
            $table->dropColumn('yarn_type_id');
            $table->dropColumn('yarn_color');
            $table->dropColumn('yarn_brand');
        });
    }
}
