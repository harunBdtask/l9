<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateWisePrintProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_wise_print_production_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('print_date');
            $table->json('print_details')->nullable();
            $table->integer('total_print_sent')->default(0);
            $table->integer('total_print_received')->default(0);
            $table->integer('total_print_rejection')->default(0);
            $table->unsignedInteger('factory_id')->nullable();
            $table->timestamps();

            $table->index('print_date');
            $table->index('factory_id');

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
        Schema::dropIfExists('date_wise_print_production_reports');
    }
}
