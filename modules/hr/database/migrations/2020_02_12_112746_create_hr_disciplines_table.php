<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_disciplines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('occurrence_date');
            $table->date('action_date');
            $table->text('occurrence_detail')->nullable();
            $table->text('investigation_member')->nullable();
            $table->text('investigation_detail')->nullable();
            $table->date('report_date');
            $table->string('action_taken', 20);
            $table->string('case_no', 10);
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('hr_disciplines');
    }
}
