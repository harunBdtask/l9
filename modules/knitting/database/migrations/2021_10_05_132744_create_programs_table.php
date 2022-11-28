<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('machine_gg');
            $table->date('program_date');
            $table->string('machine_dia');
            $table->json('fabric_color_list');
            $table->string('finish_fabric_dia');
            $table->unsignedInteger('knitting_party_id');
            $table->unsignedInteger('knitting_source_id');
            $table->unsignedInteger('fabric_sales_order_id');

            $table->integer('factory_id')->nullable();

            $table->unsignedInteger('color_range_id')->nullable();
            $table->unsignedInteger('feeder_id')->nullable();

            $table->string('machine_capacity')->nullable();
            $table->string('stitch_length')->nullable();
            $table->string('program_no')->nullable();
            $table->string('remarks')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->decimal('program_qty',8,4)->nullable();

            $table->string('status')->nullable()->comment('waiting, Running, Stop Close');

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
        Schema::dropIfExists('programs');
    }
}
