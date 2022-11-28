<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateV3TrimsStoreIssueReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v3_trims_store_issue_returns', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('store_id');
            $table->tinyInteger('return_source_id');
            $table->tinyInteger('return_basis_id');
            $table->tinyInteger('return_type_id');
            $table->string('return_challan_no');
            $table->string('input_challan_no')->nullable();
            $table->date('issue_return_date');
            $table->string('pi_number')->nullable();
            $table->date('pi_rcv_date')->nullable();
            $table->string('lc_no')->nullable();
            $table->date('lc_rcv_date')->nullable();
            $table->string('return_to')->nullable();
            $table->tinyInteger('pay_mode_id')->nullable();
            $table->tinyInteger('ready_to_approve_id')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('v3_trims_store_issue_returns');
    }
}
