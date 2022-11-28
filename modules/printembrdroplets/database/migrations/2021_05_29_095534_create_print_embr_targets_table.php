<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintEmbrTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_embr_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->date('target_date')->index();
            $table->unsignedInteger('print_factory_table_id')->index();
            $table->integer('man_power')->default(0);
            $table->integer('target_qty')->default(0);
            $table->integer('working_hour')->default(0);
            $table->string('remarks')->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('print_factory_table_id')->references('id')->on('print_factory_tables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('print_embr_targets');
    }
}
