<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnToDyesChemicalsBarcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemicals_barcodes', function (Blueprint $table) {
            $table->unsignedInteger('store_id')->nullable()->after('receive_date');
            $table->string('lot_no')->nullable()->after('life_end_days');
            $table->string('batch_no')->nullable()->after('lot_no');
            $table->string('mrr_no')->nullable()->after('batch_no');
            $table->string('sr_no')->nullable()->after('mrr_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyes_chemicals_barcodes', function (Blueprint $table) {
            $table->dropColumn('store_id');
            $table->dropColumn('lot_no');
            $table->dropColumn('batch_no');
            $table->dropColumn('mrr_no');
            $table->dropColumn('sr_no');
        });
    }
}
