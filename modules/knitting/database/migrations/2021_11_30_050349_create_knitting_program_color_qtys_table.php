<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnittingProgramColorQtysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knitting_program_color_qtys', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('plan_info_id');
            $table->unsignedInteger('knitting_program_id');
            $table->unsignedInteger('item_color_id')->default(null);
            $table->string('item_color', 100)->nullable();
            $table->string('booking_qty', 100)->default(0);
            $table->string('program_qty', 100)->default(0);
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('knitting_program_color_qtys');
    }
}
