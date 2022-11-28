<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToStyleNameTrimsStoreBinCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_bin_cards', function (Blueprint $table) {
            $table->string('style_name')->nullable()->after('unique_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_store_bin_cards', function (Blueprint $table) {
            $table->dropColumn([
                'style_name'
            ]);
        });
    }
}
