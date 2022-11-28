<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('line_no', 20);
            $table->unsignedInteger('floor_id');
            $table->tinyInteger('month_no')->default(0);
            $table->tinyInteger('day_no')->default(0);
            $table->tinyInteger('operator')->default(0);
            $table->tinyInteger('helper')->default(0);
            $table->tinyInteger('target')->default(0);
            $table->tinyInteger('wh')->default(0);
            $table->tinyInteger('input_plan')->default(0);
            $table->tinyInteger('add_man_min')->default(0);
            $table->tinyInteger('sub_man_min')->default(0);
            $table->tinyInteger('mb')->default(0);
            $table->tinyInteger('npt')->default(0);
            $table->tinyInteger('shading_problem')->default(0);
            $table->tinyInteger('late_decision')->default(0);
            $table->tinyInteger('cutting_problem')->default(0);
            $table->tinyInteger('input_problem')->default(0);
            $table->tinyInteger('late_to_get_mc')->default(0);
            $table->tinyInteger('print_mistake')->default(0);
            $table->tinyInteger('late_to_recieve_print')->default(0);
            $table->tinyInteger('line_status')->default(0);
            $table->integer('sort')->nullable();
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('lines');
    }
}
