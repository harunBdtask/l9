<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSewingLineCapacitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sewing_line_capacities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('line_id');
            $table->integer('operator')->default(0);
            $table->integer('helper')->default(0);
            $table->float('absent_percent')->default(0);
            $table->integer('working_hour')->nullable();
            $table->integer('working_minutes')->nullable();
            $table->float('line_efficiency')->default(0);
            $table->integer('capacity_available_minutes')->default(0);
            $table->unsignedInteger('factory_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sewing_line_capacities');
    }
}
