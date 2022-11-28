<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepColumnToPriceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('price_quotations', 'step')) {
                $table->integer('step')->default(0);
            }

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
            if (Schema::hasColumn('price_quotations', 'step')) {
                $table->dropColumn('step');
            }

        });
    }
}
