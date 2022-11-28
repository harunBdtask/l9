<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSubDyeingRecipeDetailsChangeRecipeFunctionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_recipe_details', function (Blueprint $table) {
            $table->unsignedBigInteger('recipe_function_id')->nullable()->change()->after('recipe_operation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_recipe_details', function (Blueprint $table) {
            $table->unsignedBigInteger('recipe_function_id')->nullable(false)->change()->after('recipe_operation_id');
        });
    }
}
