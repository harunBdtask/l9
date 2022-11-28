<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrOfficeTimeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_office_time_settings', function (Blueprint $table) {
            $table->id();
            $table->time('worker_office_time');
            $table->time('worker_late_allowed_minute');
            $table->time('staff_office_time');
            $table->time('staff_late_allowed_minute');
            $table->time('management_office_time');
            $table->time('management_late_allowed_minute');
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('hr_office_time_settings');
    }
}
