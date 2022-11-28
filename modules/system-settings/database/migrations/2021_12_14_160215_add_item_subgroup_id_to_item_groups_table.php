<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemSubgroupIdToItemGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_groups', function (Blueprint $table) {
            $table->unsignedInteger('item_subgroup_id')->nullable()->after('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_groups', function (Blueprint $table) {
            $table->dropColumn('item_subgroup_id');
        });
    }
}
