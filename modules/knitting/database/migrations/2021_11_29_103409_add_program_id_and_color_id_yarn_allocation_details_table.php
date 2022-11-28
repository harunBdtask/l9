<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgramIdAndColorIdYarnAllocationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_allocation_details', function (Blueprint $table) {
            $table->unsignedInteger('yarn_allocation_id')->nullable()->change();
            $table->string('knitting_program_id')->nullable()->after('id')->comment("Knitting Program Id");
            $table->string('knitting_program_color_id')->nullable()->after('knitting_program_id')->comment("Knitting Program Color Id");
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
            $table->dropColumn('knitting_program_id');
            $table->dropColumn('knitting_program_color_id');
        });
    }
}
