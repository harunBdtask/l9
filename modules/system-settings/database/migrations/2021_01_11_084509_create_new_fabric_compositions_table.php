<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewFabricCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_fabric_compositions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fabric_nature_id')->nullable();
            $table->string('construction', 255)->nullable();
            $table->unsignedInteger('color_range_id')->nullable();
            $table->string('gsm', 30)->nullable();
            $table->string('machine_dia', 30)->nullable();
            $table->string('finish_fabric_dia', 30)->nullable();
            $table->string('machine_gg', 30)->nullable();
            $table->string('stitch_length', 30)->nullable();
            $table->tinyInteger('status')->default(1)->comment = "1=Active, 2=Inactive";
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
        Schema::dropIfExists('new_fabric_compositions');
    }
}
