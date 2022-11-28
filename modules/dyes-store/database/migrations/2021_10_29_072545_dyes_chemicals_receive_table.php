<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DyesChemicalsReceiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyes_chemicals_receive', function (Blueprint $table) {
            $table->id();
            $table->string('system_generate_id')->nullable();
            $table->unsignedInteger('supplier_id');
            $table->date('receive_date');
            $table->string('reference_no')->nullable();
            $table->unsignedInteger('storage_location')->nullable();
            $table->string('lc_no')->nullable();
            $table->date('lc_receive_date')->nullable();
            $table->tinyInteger('readonly')->default(1);
            $table->json('details')->nullable();
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
        Schema::dropIfExists('dyes_chemicals_receive');
    }
}
