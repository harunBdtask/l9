<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_no', 20)->nullable();
            $table->unsignedInteger('factory_id');
            $table->date('issue_date');
            $table->string('issue_purpose', 40)->nullable();
            $table->string('challan_no', 40)->nullable();
            $table->enum('service_source', ['in_house', 'out_bound']);
            $table->string('service_company_type', 30)->nullable();
            $table->unsignedBigInteger('service_company_id')->nullable();

            $table->string('service_location')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->string('cutt_req_no')->nullable();

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
        Schema::dropIfExists('fabric_issues');
    }
}
