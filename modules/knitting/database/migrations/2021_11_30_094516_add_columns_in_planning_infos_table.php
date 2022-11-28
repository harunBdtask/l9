<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInPlanningInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planning_infos', function (Blueprint $table) {
            $table->string('booking_type')->after('booking_no')->nullable();
            $table->date('booking_date')->after('booking_type')->nullable();
            $table->text('po_no')->after('unique_id')->nullable();
            $table->text('gmt_color')->after('color_type')->nullable();
            $table->text('item_color')->after('gmt_color')->nullable();
            $table->unsignedInteger('fabric_nature_id')->after('production_qty')->nullable();
            $table->string('fabric_nature')->after('fabric_nature_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planning_infos', function (Blueprint $table) {
            $table->dropColumn([
                'booking_type',
                'booking_date',
                'po_no',
                'gmt_color',
                'item_color',
                'fabric_nature_id',
                'fabric_nature',
            ]);
        });
    }
}
