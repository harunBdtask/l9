<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_no', 20);
            $table->enum('transfer_criteria', ['store_to_store', 'company_to_company']);
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('to_factory_id')->nullable();
            $table->date('transfer_date');
            $table->string('challan_no', 30)->nullable();
            $table->unsignedInteger('from_store_id');
            $table->unsignedInteger('to_store_id');

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
        Schema::dropIfExists('yarn_transfers');
    }
}
