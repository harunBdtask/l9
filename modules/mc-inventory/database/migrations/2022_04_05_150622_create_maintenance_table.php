<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_maintenance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->date('last_maintenance')->nullable();
            $table->tinyInteger('tenor')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('parts_change')->default(0);
            $table->text('parts_change_description')->nullable();
            $table->string('mechanic')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('mc_maintenance');
    }
}
