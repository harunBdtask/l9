<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecipeIdToBatchMachineAllocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_batch_machine_allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_dyeing_batch_id')->nullable(true)->change();
            $table->unsignedBigInteger('sub_dyeing_recipe_id')->nullable()->after('sub_dyeing_batch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_batch_machine_allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_dyeing_batch_id')->nullable(false)->change();
            $table->dropColumn('sub_dyeing_recipe_id');
        });
    }
}
