<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnitCardYarnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knit_card_yarn_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('plan_info_id');
            $table->unsignedInteger('knitting_program_id');
            $table->unsignedInteger('knit_card_id');
            $table->unsignedInteger('knit_yarn_allocation_detail_id');
            $table->unsignedInteger('yarn_count_id');
            $table->unsignedInteger('yarn_composition_id');
            $table->string('yarn_color')->nullable();
            $table->string('brand')->nullable();
            $table->string('yarn_lot');
            $table->string('vdq')->default(0);
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
        Schema::dropIfExists('knit_card_yarn_details');
    }
}
