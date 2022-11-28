<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSoftDeleteFieldFromReportSignatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_signatures', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('deleted_by');
        });

        Schema::table('report_signature_details', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_signatures', function (Blueprint $table) {
            $table->timestamp('deleted_at', 0)->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
        });

        Schema::table('report_signature_details', function (Blueprint $table) {
            $table->timestamp('deleted_at', 0)->nullable();
        });
    }
}
