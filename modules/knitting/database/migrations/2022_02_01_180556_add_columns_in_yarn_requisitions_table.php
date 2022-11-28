<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInYarnRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_requisitions', function (Blueprint $table) {
            $table->string('attention')->nullable();
            $table->date('req_date')->nullable();
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_requisitions', function (Blueprint $table) {
            $table->dropColumn('attention');
            $table->dropColumn('req_date');
            $table->dropColumn('remarks');
        });
    }
}
