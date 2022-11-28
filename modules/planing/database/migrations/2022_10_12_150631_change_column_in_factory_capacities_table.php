<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnInFactoryCapacitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factory_capacities', function (Blueprint $table) {
            $table->renameColumn('garments_item_id', 'item_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factory_capacities', function (Blueprint $table) {
            $table->renameColumn('item_category_id', 'garments_item_id');
        });
    }
}
