<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->increments('id');
            $table->date('trn_date');
            $table->unsignedInteger('account_id');
            $table->string('trn_type')->comment('cr => credit transaction; dr => debit transaction;');
            $table->double('trn_amount', 10, 2);
            $table->string('particulars');
            $table->unsignedInteger('voucher_id');
            $table->unsignedInteger('posted_by');
            $table->unsignedInteger('factory_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal');
    }
}
