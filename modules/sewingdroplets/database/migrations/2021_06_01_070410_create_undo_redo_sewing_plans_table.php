<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUndoRedoSewingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('undo_redo_sewing_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sewing_plan_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('line_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('allocated_qty')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('required_seconds')->default(0);
            $table->string('text')->nullable();
            $table->string('plan_text')->nullable();
            $table->float('progress')->default(0);
            $table->integer('is_locked')->default(0)->comment='0=No,1=Yes';
            $table->string('board_color')->nullable();
            $table->string('notes')->nullable();
            $table->unsignedInteger('factory_id');
            $table->integer('undo_redo_status')->default(0)->comment='0=pending,1=Undo,2=Redo';
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
        Schema::dropIfExists('undo_redo_sewing_plans');
    }
}
