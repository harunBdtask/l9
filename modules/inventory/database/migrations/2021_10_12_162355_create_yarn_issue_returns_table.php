<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnIssueReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_issue_returns', function (Blueprint $table) {
            $table->id();
            $table->string('issue_return_no', 20);
            $table->unsignedInteger('factory_id');
            $table->string('issue_return_basis', 30);
            $table->string('issue_no')->nullable();
            $table->string('location')->nullable();
            $table->string('return_source')->nullable();
            $table->unsignedInteger('knitting_company_id')->nullable();
            $table->date('return_date');
            $table->string('requisition_no', 30)->nullable();
            $table->string('return_challan', 30)->nullable();

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
        Schema::dropIfExists('yarn_issue_returns');
    }
}
