<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DyesChemicalsBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyes_chemicals_barcodes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->date('receive_date');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('uom_id');
            $table->foreignId('dyes_chemicals_receive_id')->constrained('dyes_chemicals_receive')->cascadeOnDelete();
            $table->integer('life_end_days');
            $table->float('qty');
            $table->float('delivery_qty');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('dyes_chemicals_barcodes');
    }
}
