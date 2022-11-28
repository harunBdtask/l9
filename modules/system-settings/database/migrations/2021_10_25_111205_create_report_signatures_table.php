<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('page_name')->default(0);
            $table->unsignedInteger('view_button')->default(0);
            $table->unsignedInteger('is_active')->default(0);
            $table->unsignedInteger('is_template')->default(0);
            $table->string('template_name')->nullable();

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
        Schema::dropIfExists('report_signatures');
    }
}
