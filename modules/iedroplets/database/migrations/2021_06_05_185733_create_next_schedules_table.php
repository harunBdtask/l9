<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNextSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('next_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->date('next_schedule_date')->nullable();
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('line_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->date('output_finish_date')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('factory_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['floor_id', 'line_id']);
            $table->index(['buyer_id', 'order_id']);
            $table->index('output_finish_date');
            $table->index('factory_id');

            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('next_schedules');
    }
}
