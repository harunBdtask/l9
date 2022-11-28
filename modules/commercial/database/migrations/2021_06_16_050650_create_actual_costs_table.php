<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActualCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actual_costs', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->unsignedInteger('cost_head_id');
            $table->date('incurred_date_from')->nullable();
            $table->date('incurred_date_to')->nullable();
            $table->date('applying_period_from')->nullable();
            $table->date('applying_period_to')->nullable();
            $table->string('amount', 20)->nullable();
            $table->unsignedInteger('based_on')->nullable();

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
        Schema::dropIfExists('actual_costs');
    }
}
