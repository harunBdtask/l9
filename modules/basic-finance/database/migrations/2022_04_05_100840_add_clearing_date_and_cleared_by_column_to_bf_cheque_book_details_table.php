<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClearingDateAndClearedByColumnToBfChequeBookDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_cheque_book_details', function (Blueprint $table) {
            $table->date('clearing_date')->after('status')->nullable();
            $table->unsignedInteger('cleared_by')->after('clearing_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_cheque_book_details', function (Blueprint $table) {
            $table->dropColumn('clearing_date');
            $table->dropColumn('cleared_by');
        });
    }
}
