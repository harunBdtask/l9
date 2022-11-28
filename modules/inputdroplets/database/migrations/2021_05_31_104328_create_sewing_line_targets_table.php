<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSewingLineTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sewing_line_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('line_id');
            $table->date('target_date');
            $table->integer('operator')->default(0);
            $table->integer('helper')->default(0);
            $table->integer('target')->default(0);
            $table->integer('wh')->default(0);
            $table->integer('input_plan')->default(0);
            $table->integer('add_man_min')->default(0);
            $table->integer('sub_man_min')->default(0);
            $table->integer('mb')->default(0);
            $table->integer('npt')->default(0);
            $table->integer('shading_problem')->default(0);
            $table->integer('late_decision')->default(0);
            $table->integer('cutting_problem')->default(0);
            $table->integer('input_problem')->default(0);
            $table->integer('late_to_get_mc')->default(0);
            $table->integer('print_mistake')->default(0);
            $table->integer('late_to_recieve_print')->default(0);
            $table->integer('line_status')->default(0);
            $table->string('remarks')->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('floor_id');
            $table->index('line_id');
            $table->index('target_date');

            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sewing_line_targets');
    }
}
