<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_issues', function (Blueprint $table) {
            $table->id();
            $table->string('uniq_id');
            $table->unsignedInteger('factory_id');
            $table->string('issue_basis', 30)->nullable();
            $table->string('issue_purpose', 30)->nullable();
            $table->date('issue_date');
            $table->string('issue_challan_no', 30)->nullable();
            $table->string('location', 50)->nullable();
            $table->unsignedInteger('store_id')->nullable();
            $table->string('sewing_source', 30)->nullable();
            $table->unsignedInteger('sewing_composite')->nullable();
            $table->string('sewing_location', 50)->nullable();
            $table->string('remarks', 30)->nullable();

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
        Schema::dropIfExists('trims_issues');
    }
}
