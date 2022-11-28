<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bf_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bf_company_id')->constrained('bf_companies');
            $table->string('project');
            $table->string('project_head_name', 60)->nullable();
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
        Schema::dropIfExists('bf_projects');
    }
}
