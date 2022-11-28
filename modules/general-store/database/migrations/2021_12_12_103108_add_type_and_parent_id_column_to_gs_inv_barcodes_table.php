<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndParentIdColumnToGsInvBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gs_inv_barcodes', function (Blueprint $table) {
            $table->string("type")->after("status");
            $table->unsignedBigInteger("parent_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gs_inv_barcodes', function (Blueprint $table) {
            $table->dropColumn("type");
            $table->dropColumn("parent_id");
        });
    }
}
