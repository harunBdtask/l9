<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTnaTaskEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_task_entries', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->string('task_short_name', 20);
            $table->double('task_completion')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 => Active, 2=> InActive');
            $table->unsignedInteger('sequence')->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('group_sequence')->nullable();
            $table->string('integration_with_entry_page', 50)->nullable();
            $table->tinyInteger('actual_date_range_calculate')->default(1)
                ->comment('1 => Auto, 2=> Manual');
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
        Schema::dropIfExists('tna_task_entries');
    }
}
