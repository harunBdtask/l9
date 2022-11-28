<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLdNoColumnToSubDyeingRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_recipes', function (Blueprint $table) {
            $table->string('ld_no')->nullable()->after('recipe_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_recipes', function (Blueprint $table) {
            $table->dropColumn('ld_no');
        });
    }
}
