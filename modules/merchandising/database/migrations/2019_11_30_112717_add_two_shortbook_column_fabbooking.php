<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoShortbookColumnFabbooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_fabric_booking', function (Blueprint $table) {
            //				$table->double('second_short_book')->nullable();
//				$table->double('third_short_book')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_fabric_booking', function (Blueprint $table) {
            //			$table->dropColumn('second_short_book');
//			$table->dropColumn('third_short_book');
        });
    }
}
