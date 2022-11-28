<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBfCompaniesRemoveFactoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('bf_companies', 'factory_address')) {
            Schema::table('bf_companies', function (Blueprint $table) {
                $table->dropColumn('factory_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if ((Schema::hasColumn('bf_companies', 'factory_address')) === false) {
            Schema::table('bf_companies', function (Blueprint $table) {
                $table->string('factory_address', 60)->nullable();
            });
        }
    }
}
