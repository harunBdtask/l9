<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialParameterSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_parameter_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('factory_id');
            $table->string('date_from');
            $table->string('date_to');
            $table->string('working_day')->nullable();
            $table->string('bep_cm')->nullable();
            $table->string('asking_profit');
            $table->string('factory_machine');
            $table->string('monthly_cm_expense');
            $table->string('working_hour');
            $table->string('cost_per_minute');
            $table->string('actual_cm')->nullable();
            $table->string('asking_avg_rate');
            $table->string('max_profit')->nullable();
            $table->string('depreciation_amortization')->nullable();
            $table->string('interest_expenses')->nullable();
            $table->string('income_tax')->nullable();
            $table->string('status', 20);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_parameter_setups');
    }
}
