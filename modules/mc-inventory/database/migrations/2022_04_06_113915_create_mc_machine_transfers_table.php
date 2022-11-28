<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMcMachineTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_machine_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('transfer_from');
            $table->unsignedBigInteger('transfer_to');
            $table->text('reason')->nullable();
            $table->text('attention')->nullable();
            $table->string('contact_no')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('machine_id')->references('id')->on('mc_machines')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('mc_machine_transfers');
    }
}
