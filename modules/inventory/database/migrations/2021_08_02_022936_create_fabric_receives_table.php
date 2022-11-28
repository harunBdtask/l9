<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_receives', function (Blueprint $table) {
            $table->id();
            $table->string('receive_no', 20)->nullable();
            $table->unsignedInteger('factory_id');
            $table->string('factory_location')->nullable();
            $table->date('receive_date');
            $table->unsignedBigInteger('store_id');
            $table->string('receive_basis', 40)->nullable();
            $table->string('receivable_type', 30)->nullable();
            $table->unsignedBigInteger('receivable_id')->nullable();
            $table->enum('dyeing_source', ['in_house', 'out_bound']);
            $table->string('dyeing_supplier_type', 30)->nullable();
            $table->unsignedBigInteger('dyeing_supplier_id')->nullable();
            $table->string('dyeing_supplier_address')->nullable();
            $table->string('receive_challan', 30)->nullable();
            $table->json('po_no')->nullable();
            $table->string('grey_issue_challan', 3)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('exchange_rate', 20)->nullable();
            $table->string('lc_sc_no', 30)->nullable();
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
        Schema::dropIfExists('fabric_receives');
    }
}
