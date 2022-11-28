<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnittingProgramMachineDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knitting_program_machine_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_info_id');
            $table->unsignedBigInteger('knitting_program_id');
            $table->unsignedBigInteger('machine_id');
            $table->string('distribution_qty')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knitting_program_machine_distributions');
    }
}
