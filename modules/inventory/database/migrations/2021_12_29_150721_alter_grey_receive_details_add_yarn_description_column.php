<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGreyReceiveDetailsAddYarnDescriptionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grey_receive_details', function (Blueprint $table) {
            $table->text('yarn_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grey_receive_details', function (Blueprint $table) {
            $table->dropColumn('yarn_description');
        });
    }
}
