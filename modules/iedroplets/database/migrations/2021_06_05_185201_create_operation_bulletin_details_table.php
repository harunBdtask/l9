<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationBulletinDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_bulletin_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operation_bulletin_id');
            $table->unsignedInteger('task_id');
            $table->tinyInteger('special_task')->default(0);
            $table->unsignedInteger('machine_type_id');
            $table->tinyInteger('special_machine')->default(0);
            $table->unsignedInteger('guide_or_folder_id')->nullable();
            $table->unsignedInteger('operator_skill_id')->nullable();
            $table->integer('work_station')->default(0);
            $table->integer('time')->default(0);
            $table->float('idle_time')->default(0);
            $table->integer('new_work_station')->default(0);
            $table->float('new_time')->default(0);
            $table->float('new_idle_time')->nullable();
            $table->integer('hourly_target')->default(0);
            $table->string('remarks')->nullable();
            $table->unsignedInteger('factory_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('operation_bulletin_id')->references('id')->on('operation_bulletins')->onDelete('cascade');
            $table->foreign('machine_type_id')->references('id')->on('machine_types')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('guide_or_folder_id')->references('id')->on('guide_or_folders')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_bulletin_details');
    }
}
