<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemWiseQtyColumnInPoItemColorSizeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_item_color_size_details', function (Blueprint $table) {
            $table->string('item_wise_quantity')->after('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_item_color_size_details', function (Blueprint $table) {
            $table->dropColumn('item_wise_quantity');
        });
    }
}
