<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddColumnToSubDyeingPeachDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_peach_details', function (Blueprint $table) {
            $table->date('production_date')->after('sub_textile_order_details_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_peach_details', function (Blueprint $table) {
            $table->dropColumn(['production_date']);
        });
    }
}
