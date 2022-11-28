<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyFieldsToCuttingQtyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cutting_qty_requests', function (Blueprint $table) {
            $table->json('additional_ex_cut')->nullable()->after('color_id');
            $table->json('additional_cut_qty')->nullable()->after('additional_ex_cut');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cutting_qty_requests', function (Blueprint $table) {
            $table->dropColumn(['additional_cut_qty', 'additional_ex_cut']);
        });
    }
}
