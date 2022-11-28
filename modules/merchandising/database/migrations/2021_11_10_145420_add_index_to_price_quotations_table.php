<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToPriceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_quotations', function (Blueprint $table) {
            $table->index('quotation_id');
            $table->index('factory_id');
            $table->index('buyer_id');
            $table->index('style_name');
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
            $table->dropIndex(['quotation_id']);
            $table->dropIndex(['factory_id']);
            $table->dropIndex(['buyer_id']);
            $table->dropIndex(['style_name']);
        });
    }
}
