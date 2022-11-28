<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProductionDateTypeInTqmSewingDhuDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tqm_sewing_dhu_details', function (Blueprint $table) {
            $table->date('production_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tqm_sewing_dhu_details', function (Blueprint $table) {
            $table->unsignedInteger('production_date')->change();
        });
    }
}
