<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceQuotationStyleEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_quotation_style_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_quotation_id');
            $table->string('pcs_per_carton')->nullable();
            $table->string('cbm_per_carton')->nullable();
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
        Schema::dropIfExists('price_quotation_style_entries');
    }
}