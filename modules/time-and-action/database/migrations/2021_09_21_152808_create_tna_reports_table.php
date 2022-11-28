<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTnaReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->index();
            $table->unsignedInteger('buyer_id')->index();
            $table->unsignedInteger('task_id')->index();
            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('po_id')->nullable()->index();
            $table->tinyInteger('based_on')->comment('1 => po_wise, 2 => style_wise');
            $table->integer('lead_time');
            $table->integer('execution_days');
            $table->integer('deadline');
            $table->integer('notice_before');
            $table->integer('task_sequence');
            $table->date('start_date');
            $table->date('finish_date');
            $table->date('actual_start_date')->nullable();
            $table->date('actual_finish_date')->nullable();
            $table->string('early_start')->nullable();
            $table->string('early_finish')->nullable();
            $table->string('delay_start')->nullable();
            $table->string('delay_finish')->nullable();
            $table->string('comment_start')->nullable();
            $table->string('comment_finish')->nullable();
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
        Schema::dropIfExists('tna_reports');
    }
}
