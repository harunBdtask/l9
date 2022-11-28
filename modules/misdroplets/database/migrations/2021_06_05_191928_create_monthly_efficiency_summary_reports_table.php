<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyEfficiencySummaryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_efficiency_summary_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('report_date');
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('line_id');
            $table->double('used_minutes', 10, 2);
            $table->double('produced_minutes', 10, 2);
            $table->double('line_efficiency', 10, 2);
            $table->unsignedInteger('factory_id');
            $table->timestamps();

            $table->index(['floor_id','line_id']);
            $table->index('report_date');

            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
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
        Schema::dropIfExists('monthly_efficiency_summary_reports');
    }
}
