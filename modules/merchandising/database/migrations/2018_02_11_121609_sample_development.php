<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SampleDevelopment extends Migration
{
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('agent_id');
            $table->string('sample_ref_no');
            $table->date('receive_date');
            $table->text('sample_files')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('team_leader')->nullable();
            $table->unsignedInteger('dealing_merchant')->nullable();
            $table->string('season')->nullable();
            $table->unsignedInteger('currency')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('samples');
    }
}
