<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCommentInErpMenuViewStatusColumnInGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            DB::statement("ALTER TABLE `garments_production_entries` CHANGE `erp_menu_view_status` `erp_menu_view_status` INT(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=ERP Menu,2=PROTRACKER Menu, 3=Textile Menu';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            DB::statement("ALTER TABLE `garments_production_entries` CHANGE `erp_menu_view_status` `erp_menu_view_status` INT(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=ERP Menu,2=PROTRACKER Menu';");
        });
    }
}
