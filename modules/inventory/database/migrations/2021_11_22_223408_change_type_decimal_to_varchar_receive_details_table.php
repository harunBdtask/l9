<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeDecimalToVarcharReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_receive_details', function (Blueprint $table) {
            $table->string('rate',255)->change();
            $table->string('amount',255)->change();
            $table->string('balance_qty',255)->change();
            $table->string('receive_qty',255)->change();
            $table->string('book_currency',255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_receive_details', function (Blueprint $table) {
            $table->decimal('rate', 10, 4)->change();
            $table->decimal('amount', 10, 4)->change();
            $table->decimal('receive_qty', 10, 4)->change();
            $table->decimal('balance_qty', 10, 4)->change();
            $table->decimal('book_currency', 10, 4)->change();
        });
    }
}
