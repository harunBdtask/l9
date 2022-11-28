<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnitCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knit_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('plan_info_id');
            $table->unsignedInteger('knitting_program_id');
            $table->unsignedInteger('current_machine_id');
            $table->string('knit_card_no');
            $table->string('assign_qty')->default(0);
            $table->string('production_target_qty')->default(0);
            $table->date('knit_card_date')->nullable();
            $table->string('program_dia')->nullable();
            $table->string('program_gg')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('machine_allocation_status')->default(0);
            $table->tinyInteger('current_production_status')->default(0);
            $table->tinyInteger('current_machine_priority')->default(0);
            $table->text('current_production_remarks');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('knit_cards');
    }
}
