<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillNoToDyesChemicalsReceive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemicals_receive', function (Blueprint $table) {
            $table->string('bill_no')->nullable()->after('reference_no');
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
            $table->dropColumn([
                'bill_no'
            ]);
        });
    }
}
