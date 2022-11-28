<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnToReportSignatureDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_signature_details', function (Blueprint $table) {
            $table->text('name')->nullable()->change();
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_signature_details', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->dropColumn(['user_id']);
        });
    }
}
