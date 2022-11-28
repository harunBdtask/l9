<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLienDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lien_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lien_id');

            $table->string('buyer_name')->nullable();
            $table->date('sales_contract_date')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->string('internal_file_no',255)->nullable();
            $table->unsignedInteger('sales_contract_id')->nullable();
            $table->string('sales_contract_no',255)->nullable();
            $table->string('sales_contract_value',255)->nullable();

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
        Schema::dropIfExists('lien_details');
    }
}
