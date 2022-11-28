<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAllocatedQtyIntToVarcharInYarnAllocationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_allocation_details', function (Blueprint $table) {
            $table->string('allocated_qty')->change();
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
            $table->integer('allocated_qty')->change();
        });
    }
}
