<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->index('job_no');
            $table->index('factory_id');
            $table->index('buyer_id');
            $table->index('style_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropIndex(['job_no']);
            $table->dropIndex(['factory_id']);
            $table->dropIndex(['buyer_id']);
            $table->dropIndex(['style_name']);
        });
    }
}
