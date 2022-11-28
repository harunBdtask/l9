<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterExportLcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('export_lc', function (Blueprint $table) {
            $table->dropColumn('currency');
            $table->string('bank_file_no')->nullable()->change();
            $table->unsignedBigInteger('currency_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('export_lc', function (Blueprint $table) {
            $table->string('currency', 10)->nullable();
            $table->dropColumn('currency_id');
            $table->string('bank_file_no')->nullable(false)->change();
        });
    }
}
