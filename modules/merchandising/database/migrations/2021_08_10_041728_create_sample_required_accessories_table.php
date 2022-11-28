<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleRequiredAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_required_accessories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_requisition_id');
            $table->unsignedBigInteger('sample_id');
            $table->unsignedBigInteger('gmts_item_id');
            $table->unsignedInteger('item_id');
            $table->string('brand_sup_ref')->nullable();
            $table->string('description')->nullable();
            $table->decimal('rate', 10, 2);
            $table->decimal('req_qty', 10, 2);
            $table->decimal('total_qty', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('uom_id');
            $table->string('uom_value');
            $table->string('remarks')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('sample_required_accessories');
    }
}
