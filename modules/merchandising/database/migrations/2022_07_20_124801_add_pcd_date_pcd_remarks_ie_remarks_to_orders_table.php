<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPcdDatePcdRemarksIeRemarksToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('pcd_date')->nullable()->after('images');
            $table->string('pcd_remarks')->nullable()->after('pcd_date');
            $table->string('ie_remarks')->nullable()->after('pcd_remarks');
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
            $table->dropColumn([
                'pcd_date',
                'pcd_remarks',
                'ie_remarks'
            ]);
        });
    }
}
