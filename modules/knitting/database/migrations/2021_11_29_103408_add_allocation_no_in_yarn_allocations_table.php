<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllocationNoInYarnAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_allocations', function (Blueprint $table) {
            $table->string('allocation_no')->nullable()->after('id')->comment("auto generated key");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_allocations', function (Blueprint $table) {
            $table->dropColumn('allocation_no');
        });
    }
}
