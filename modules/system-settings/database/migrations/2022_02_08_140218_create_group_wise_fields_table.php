<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupWiseFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('group_wise_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factory_id');
            $table->string('group_name');
            $table->json('fields')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_wise_fields');
    }
}
