<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTnaTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_id')->nullable()->comment('Auto Generated');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('lead_time');
            $table->unsignedTinyInteger('tna_for')->nullable();
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
        Schema::dropIfExists('tna_templates');
    }
}
