<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishingTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finishing_targets', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('finishing_floor_id');
            $table->unsignedInteger('finishing_table_id')->nullable();
            $table->unsignedInteger('iron_target')->nullable();
            $table->unsignedInteger('qc_pass_target')->nullable();
            $table->unsignedInteger('poly_target')->nullable();
            $table->unsignedInteger('ctn_target')->nullable();
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
        Schema::dropIfExists('finishing_targets');
    }
}
