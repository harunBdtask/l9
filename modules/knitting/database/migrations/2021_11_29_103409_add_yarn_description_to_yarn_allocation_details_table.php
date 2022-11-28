<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYarnDescriptionToYarnAllocationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_allocation_details', function (Blueprint $table) {
            $table->string('yarn_description')->nullable()
                ->after('knitting_program_color_id')
                ->comment("Knitting Yarn Allocation Description");
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
            $table->dropColumn('yarn_description');
        });
    }
}
