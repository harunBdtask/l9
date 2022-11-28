<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_item_color_size_details', function (Blueprint $table) {
            $table->dropColumn('ratio_matrix');
            $table->dropColumn('quantity_matrix');
            $table->dropColumn('colors');
            $table->dropColumn('sizes');
        });

        Schema::table('po_item_color_size_details', function (Blueprint $table) {
            $table->json('ratio_matrix')->nullable()->after('quantity');
            $table->json('quantity_matrix')->nullable()->after('ratio_matrix');
            $table->json('colors')->nullable()->after('quantity_matrix');
            $table->json('sizes')->nullable()->after('colors');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('item_details');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->json('item_details')->nullable()->after('region');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
