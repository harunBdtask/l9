<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatureHonsMastersOthersToHrEmployeeDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employee_documents', function (Blueprint $table) {
            $table->string('signature')->after('medical_certificate')->nullable();
            $table->string('masters')->after('signature')->nullable();
            $table->string('hons')->after('masters')->nullable();
            $table->string('others')->after('hons')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_employee_documents', function (Blueprint $table) {
            $table->dropColumn('signature');
            $table->dropColumn('masters');
            $table->dropColumn('hons');
            $table->dropColumn('others');
        });
    }
}
