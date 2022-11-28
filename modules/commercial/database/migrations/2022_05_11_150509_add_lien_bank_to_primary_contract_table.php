<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLienBankToPrimaryContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('primary_master_contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('lien_bank_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('primary_master_contracts', function (Blueprint $table) {
            $table->dropColumn('lien_bank_id');
        });
    }
}
