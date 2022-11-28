<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePqCommissionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pq_commission_details', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_id', 20)->nullable()->comment = "price_quotations table quotation_id";
            $table->tinyInteger('particular')->nullable()->comment = "1=Foreign,2=Local";
            $table->tinyInteger('commission_base')->nullable()->comment = "1=In Percentage,2=Per Dzn,3=Per Pcs";
            $table->string('commission_rate', 60)->nullable();
            $table->string('amount', 60)->nullable();
            $table->tinyInteger('status')->default(1)->comment = "1=Active,2=Inactive";
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
        Schema::dropIfExists('pq_commission_details');
    }
}
