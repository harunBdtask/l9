<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('approval_detailable_id');
            $table->string('approval_detailable_type');
            $table->string('page_name');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('priority');
            $table->tinyInteger('type')->comment('1 = unapproved, 2 = approve, 3 = rework, 4 = cancel');
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
        Schema::dropIfExists('approval_details');
    }
}
