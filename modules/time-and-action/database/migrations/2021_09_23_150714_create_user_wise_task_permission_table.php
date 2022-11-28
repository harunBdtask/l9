<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWiseTaskPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wise_task_permission', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('task_id');
            $table->string('plan_date_choice')->default(0)->comment('0=No,1=Yes');
            $table->string('actual_date_choice')->default(0)->comment('0=No,1=Yes');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('user_wise_task_permission');
    }
}
