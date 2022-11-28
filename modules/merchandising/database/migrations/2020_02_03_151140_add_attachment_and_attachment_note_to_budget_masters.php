<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentAndAttachmentNoteToBudgetMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_masters', function (Blueprint $table) {
//            $table->string('attachment')->after('remarks')->nullable();
//            $table->string('attachment_note')->after('attachment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_masters', function (Blueprint $table) {
//            $table->dropColumn('attachment');
//            $table->dropColumn('attachment_note');
        });
    }
}
