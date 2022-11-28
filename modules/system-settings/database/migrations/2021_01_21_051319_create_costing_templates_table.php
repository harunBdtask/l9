<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostingTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costing_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->string('template_name')->nullable();
            $table->string('type')->nullable();
            $table->text('details')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('costing_templates');
    }
}
