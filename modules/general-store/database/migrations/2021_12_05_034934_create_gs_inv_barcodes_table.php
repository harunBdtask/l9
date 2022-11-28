<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsInvBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gs_inv_barcodes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('brand_id')->nullable();
            $table->unsignedInteger('voucher_id');
            $table->float('qty');
            $table->boolean('status')->default(true);
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('gs_inv_barcodes');
    }
}
