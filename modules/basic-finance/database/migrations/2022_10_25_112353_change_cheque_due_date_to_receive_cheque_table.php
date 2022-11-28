<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeChequeDueDateToReceiveChequeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receive_cheques', function (Blueprint $table) {
            $table->date('cheque_due_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receive_cheques', function (Blueprint $table) {
            $table->date('cheque_due_date')->nullable(false)->change();
        });
    }
}
