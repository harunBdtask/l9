<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStyleNameToTrimsStoreMrr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_mrr', function (Blueprint $table) {
            $table->string('style_name')->nullable()->after('mrr_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_store_mrr', function (Blueprint $table) {
            $table->dropColumn([
               'style_name'
            ]);
        });
    }
}
