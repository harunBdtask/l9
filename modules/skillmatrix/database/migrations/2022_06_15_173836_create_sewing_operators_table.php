<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSewingOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sewing_operators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('operator_id')->nullable();
            $table->string('operator_grade')->nullable();
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('line_id')->nullable();
            $table->double('present_salary', 8, 2)->default(0);
            $table->date('joinning_date')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('sewing_operators');
    }
}
