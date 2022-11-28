<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGarmentsItemIdInBundleCardGenerationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bundle_card_generation_details', function (Blueprint $table) {
            $table->unsignedInteger('garments_item_id')->nullable()->after('order_id')->index();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bundle_card_generation_details', function (Blueprint $table) {
            $table->dropColumn('garments_item_id');
        });
    }
}
