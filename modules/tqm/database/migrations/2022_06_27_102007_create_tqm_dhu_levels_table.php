<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTqmDhuLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tqm_dhu_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->index();
            $table->tinyInteger('section')->comment("1=Cutting, 2=Sewing, 3=Finishing")->index();
            $table->float('level');
            $table->tinyInteger('comparison_status')->comment("1=Greater Than, 2=Equal, 3=Less Than");
            $table->string('color')->nullable();
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
        Schema::dropIfExists('tqm_dhu_levels');
    }
}
