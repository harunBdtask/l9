<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnToCommercialCostMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commercial_cost_methods', function (Blueprint $table) {
            $table->string('type')->default('pq');
            $table->unsignedInteger('factory_id')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commercial_cost_methods', function (Blueprint $table) {
//            $table->dropColumn('type');
            $table->dropColumn('factory_id');
        });
    }
}
