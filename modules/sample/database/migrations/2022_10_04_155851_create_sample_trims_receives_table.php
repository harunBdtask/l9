<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleTrimsReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_trims_receives', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 100)->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->date('receive_date')->nullable();
            $table->unsignedInteger('trims_issue_id')->nullable();
            $table->string('trims_issue_unique_id')->nullable();
            $table->string('issue_challan_no')->nullable();
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
        Schema::dropIfExists('sample_trims_receives');
    }
}
