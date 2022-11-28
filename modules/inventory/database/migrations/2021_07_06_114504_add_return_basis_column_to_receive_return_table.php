<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnBasisColumnToReceiveReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_receive_returns', function (Blueprint $table) {
            $table->string('return_basis')->nullable();
            $table->json('po_no')->nullable()->comment('Array')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_receive_returns', function (Blueprint $table) {
            $table->dropColumn('return_basis');

        });
    }
}
