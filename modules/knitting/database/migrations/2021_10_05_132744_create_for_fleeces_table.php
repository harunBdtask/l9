<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForFleecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('for_fleeces', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('count_id')->nullable();
            $table->unsignedInteger('program_id')->nullable();
            $table->decimal('in_percent',8,4)->nullable();
            $table->decimal('percent_wise_qty',8,0)->nullable();

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
        Schema::dropIfExists('for_fleeces');
    }
}
