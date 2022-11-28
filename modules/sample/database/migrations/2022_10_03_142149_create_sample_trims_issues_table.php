<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleTrimsIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_trims_issues', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 100)->nullable();
            $table->string('issue_challan_no', 100)->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->integer('issue_basis_id')->nullable();
            $table->string('delivery_to')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->string('style_name', 100)->nullable();
            $table->unsignedInteger('sample_id')->nullable();
            $table->unsignedInteger('to_buyer_id')->nullable();
            $table->string('to_style_name', 100)->nullable();
            $table->unsignedInteger('to_sample_id')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('remarks')->nullable();
            $table->json('total_calculation')->nullable();
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
        Schema::dropIfExists('sample_trims_issues');
    }
}
