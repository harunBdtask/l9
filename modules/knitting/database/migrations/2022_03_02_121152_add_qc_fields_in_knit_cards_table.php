<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQcFieldsInKnitCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_cards', function (Blueprint $table) {
            $table->string('qc_pass_qty')->nullable()->default(0)
                ->after('current_production_remarks');

            $table->tinyInteger('qc_pending_status')->nullable()->default(0)
                ->after('qc_pass_qty')
                ->comment('0=Pending,1=Done');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knit_cards', function (Blueprint $table) {
            $table->dropColumn('qc_pass_qty');
            $table->dropColumn('qc_pending_status');
        });
    }
}
