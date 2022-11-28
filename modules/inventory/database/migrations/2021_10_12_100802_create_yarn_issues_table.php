<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_no', 20);
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->string('issue_basis');
            $table->string('issue_purpose');
            $table->date('issue_date');
            $table->unsignedInteger('issue_to');
            $table->string('fabric_booking_no', 30)->nullable();
            $table->string('knitting_source', 30)->nullable();
            $table->string('challan_no')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('loan_party_id')->nullable();
            $table->unsignedInteger('sample_type_id')->nullable();
            $table->string('style_reference')->nullable();
            $table->string('buyer_job_no')->nullable();
            $table->string('service_booking')->nullable();
            $table->unsignedTinyInteger('ready_to_approve')->nullable();
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('yarn_issues');
    }
}
