<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBfRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_realizations', function (Blueprint $table) {
            $table->json('buyers')->nullable()->after('currency_id');
            $table->json('styles')->nullable()->after('buyers');
            $table->json('po_numbers')->nullable()->after('styles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_realizations', function (Blueprint $table) {
            $table->dropColumn('buyers');
            $table->dropColumn('styles');
            $table->dropColumn('po_numbers');
        });
    }
}
