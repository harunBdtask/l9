<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactoryIdColumnToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_accounts', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->default(0)->comment('0 for head office');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_accounts', function (Blueprint $table) {
            $table->dropColumn('factory_id');
        });
    }
}
