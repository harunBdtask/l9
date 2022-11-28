<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoryCapacitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory_capacities', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('floor_id')->comment('floors table');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('garments_item_id');
            $table->string('smv')->nullable()->comment('must be numeric');
            $table->string('efficiency')->nullable()->comment('must be numeric');
            $table->integer('operator_machine')->default(0);
            $table->integer('helper')->default(0);
            $table->integer('wh')->default(0);
            $table->integer('capacity_pcs')->default(0);
            $table->integer('capacity_available_mins')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('factory_capacities');
    }
}
