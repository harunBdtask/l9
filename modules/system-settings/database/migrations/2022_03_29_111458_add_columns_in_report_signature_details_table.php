<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInReportSignatureDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_signature_details', function (Blueprint $table) {
            $table->string('image')->after('name')->nullable();
            $table->tinyInteger('signature_type')->after('image')->nullable()->comment('1=name, 2=image');
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
            $table->dropColumn('image');
            $table->dropColumn('signature_type');
        });
    }
}
