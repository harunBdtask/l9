<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsApproveColumnToPriceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_quotations', function (Blueprint $table) {
            $table->string('is_approve')->nullable()->after('ready_to_approve');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_quotations', function (Blueprint $table) {
            $table->dropColumn('is_approve');
        });
    }
}
