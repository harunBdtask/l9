<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPiColumnsToDyesChemicalsReceiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemicals_receive', function (Blueprint $table) {
            $table->tinyInteger('receive_basis')->default(1)->after('system_generate_id');
            $table->unsignedInteger('receive_basis_id')->nullable()->after('receive_basis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyes_chemicals_receive', function (Blueprint $table) {
            $table->dropColumn('receive_basis');
            $table->dropColumn('receive_basis_id');
        });
    }
}
