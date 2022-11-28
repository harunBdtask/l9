<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDyeingRecipeOperationIdToDyeingOperationFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_operation_functions', function (Blueprint $table) {
            $table->renameColumn('dye_receipe_operation_id', 'dyeing_recipe_operation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_operation_functions', function (Blueprint $table) {
            $table->renameColumn('dyeing_recipe_operation_id', 'dye_receipe_operation_id');
        });
    }
}
