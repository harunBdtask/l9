<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBToBMarginLcDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_to_b_margin_lc_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('b_to_b_margin_lc_id');
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('export_lc_id')->nullable();
            $table->unsignedInteger('sales_contract_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();

            $table->string('lc_sc_no', 100)->nullable();
            $table->string('lc_sc_value', 100)->nullable();
            $table->string('current_distribution', 30)->nullable();
            $table->string('cumulative_distribution', 30)->nullable();
            $table->string('occupied_percentage', 20)->nullable();

            $table->unsignedTinyInteger('status')
                ->default(1)
                ->comment('1=Active, 2=Inactive, 3=Cancelled');


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
        Schema::dropIfExists('b_to_b_margin_lc_details');
    }
}
