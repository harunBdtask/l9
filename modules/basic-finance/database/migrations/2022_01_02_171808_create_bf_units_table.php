<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bf_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bf_company_id')->constrained('bf_companies');
            $table->foreignId('bf_project_id')->constrained('bf_projects');
            $table->string('unit');
            $table->string('unit_head_name', 60)->nullable();
            $table->string('phone_no', 60)->nullable();
            $table->string('email', 60)->nullable();
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
        Schema::dropIfExists('bf_units');
    }
}
