<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcActualDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_actual_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ac_company_id')->constrained('ac_companies');
            $table->foreignId('ac_unit_id')->constrained('ac_units');
            $table->foreignId('ac_cost_center_id')->constrained('ac_departments');
            $table->string('name', 128);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_actual_departments');
    }
}
