<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGarmentsItemIdInSewingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sewing_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('garments_item_id')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sewing_plans', function (Blueprint $table) {
            $table->dropColumn('garments_item_id');
        });
    }
}
