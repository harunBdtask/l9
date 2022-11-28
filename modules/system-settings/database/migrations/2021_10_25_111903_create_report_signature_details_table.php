<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportSignatureDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_signature_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('report_signature_id')->nullable();
            $table->string('designation')->nullable();
            $table->string('name')->nullable();
            $table->integer('sequence')->nullable();
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
        Schema::dropIfExists('report_signature_details');
    }
}
