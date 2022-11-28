<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorSizeSummaryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_size_summary_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('buyer_id')->nullable()->index();
            $table->unsignedInteger('order_id')->nullable()->index();
            $table->unsignedInteger('purchase_order_id')->nullable()->index();
            $table->unsignedInteger('color_id')->nullable()->index();
            $table->unsignedInteger('size_id')->nullable()->index();
            $table->integer('total_cutting')->default(0);
            $table->integer('total_cutting_rejection')->default(0);
            $table->integer('total_input')->default(0);
            $table->integer('total_sewing_output')->default(0);
            $table->integer('total_sewing_rejection')->default(0);
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('color_size_summary_reports');
    }
}
