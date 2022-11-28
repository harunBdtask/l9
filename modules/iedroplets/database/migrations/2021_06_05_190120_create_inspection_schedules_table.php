<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('order_id');
            $table->date('inspection_date');
            $table->unsignedInteger('inspection_quantity');
            $table->string('remarks', 70)->nullable();
            $table->boolean('status')->default(0)->comment='0=running, 1=completed';
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();

            $table->index('order_id');
            $table->index('inspection_date');
            $table->index('factory_id');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inspection_schedules');
    }
}
