<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNameToDyeingRecipeOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_recipe_operations', function (Blueprint $table) {
            $table->renameColumn('dye_re_operation_name', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_recipe_operations', function (Blueprint $table) {
            $table->renameColumn('name', 'dye_re_operation_name');
        });
    }
}
