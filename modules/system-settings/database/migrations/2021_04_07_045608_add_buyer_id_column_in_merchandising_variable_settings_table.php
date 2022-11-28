<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuyerIdColumnInMerchandisingVariableSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchandising_variable_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_id')->nullable()->after('factory_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchandising_variable_settings', function (Blueprint $table) {
            $table->dropColumn('buyer_id');
        });
    }
}
