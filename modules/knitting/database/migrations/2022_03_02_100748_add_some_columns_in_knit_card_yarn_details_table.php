<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsInKnitCardYarnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_card_yarn_details', function (Blueprint $table) {
            $table->renameColumn('brand', 'yarn_brand');
            $table->unsignedInteger('yarn_type_id')->after('yarn_composition_id')->nullable();
            $table->unsignedInteger('store_id')->after('yarn_type_id')->nullable();
            $table->unsignedInteger('uom_id')->after('store_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knit_card_yarn_details', function (Blueprint $table) {
            $table->renameColumn('yarn_brand', 'brand');
            $table->dropColumn('store_id');
            $table->dropColumn('yarn_type_id');
            $table->dropColumn('uom_id');
        });
    }
}
