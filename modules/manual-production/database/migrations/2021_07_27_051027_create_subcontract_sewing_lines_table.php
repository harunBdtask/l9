<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcontractSewingLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcontract_sewing_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subcontract_factory_profile_id');
            $table->unsignedInteger('subcontract_sewing_floor_id');
            $table->string('floor_name');
            $table->string('line_name');
            $table->string('sorting');
            $table->string('responsible_person')->nullable();
            $table->tinyInteger('status')->default(1)->comment("1=Active,0=Inactive");
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
        Schema::dropIfExists('subcontract_sewing_lines');
    }
}
