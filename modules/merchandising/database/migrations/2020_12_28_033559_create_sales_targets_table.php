<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('buying_agent_id');
            $table->unsignedInteger('team_leader_id');
            $table->string('month');
            $table->integer('year');
            $table->date('deleted_at')->nullable();

            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('buying_agent_id')->references('id')->on('buying_agent')->onDelete('cascade');
            $table->foreign('team_leader_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('sales_targets');
    }
}
