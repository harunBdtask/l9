<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCurrentProductionRemarksColumnInKnitCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_cards', function (Blueprint $table) {
            $table->text('current_production_remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knit_cards', function (Blueprint $table) {
            $table->text('current_production_remarks')->nullable(false)->change();
        });
    }
}
