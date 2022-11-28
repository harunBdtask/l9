<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addBasisDetailsUniqueYarnReceiveDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_receive_details', function (Blueprint $table) {
            $table->string('basis_details_unique',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_receive_details', function (Blueprint $table) {
            $table->dropColumn('basis_details_unique');
        });
    }
}
