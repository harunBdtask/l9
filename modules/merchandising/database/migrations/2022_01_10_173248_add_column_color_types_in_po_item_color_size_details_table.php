<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnColorTypesInPoItemColorSizeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_item_color_size_details', function (Blueprint $table) {
            $table->json('color_types')->nullable()->after('sizes');
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
            $table->dropColumn('color_types');
        });
    }
}
