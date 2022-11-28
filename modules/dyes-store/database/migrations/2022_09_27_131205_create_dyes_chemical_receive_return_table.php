<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDyesChemicalReceiveReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyes_chemical_receive_return', function (Blueprint $table) {
            $table->id();
            $table->string('receive_id');
            $table->string('challan_no')->nullable();
            $table->string('supplier_id')->nullable();
            $table->date('return_date');
            $table->tinyInteger('readonly')->default(1);
            $table->json('details')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('dyes_chemical_receive_return');
    }
}
