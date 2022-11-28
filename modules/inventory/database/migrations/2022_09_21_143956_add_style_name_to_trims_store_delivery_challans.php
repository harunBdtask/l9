<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStyleNameToTrimsStoreDeliveryChallans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_delivery_challans', function (Blueprint $table) {
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
        Schema::table('trims_store_delivery_challans', function (Blueprint $table) {
            $table->dropColumn('style_name');
        });
    }
}
