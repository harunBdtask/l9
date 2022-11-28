<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFinancialParameterSetupChangeSomeFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financial_parameter_setups', function (Blueprint $table) {
            $table->string('asking_profit')->nullable()->change();

            $table->string('factory_machine')->nullable()->change();

            $table->string('monthly_cm_expense')->nullable()->change();

            $table->string('working_hour')->nullable()->change();

            $table->string('cost_per_minute')->nullable()->change();

            $table->string('asking_avg_rate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financial_parameter_setups', function (Blueprint $table) {
        });
    }
}
