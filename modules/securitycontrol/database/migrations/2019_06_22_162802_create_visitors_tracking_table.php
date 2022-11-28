<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorsTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('designation');
            $table->string('company_name');
            $table->string('mobile_number');
            $table->string('email')->nullable();
            $table->string('meeting_person');
            $table->string('registration_id');
            $table->string('in_time')->nullable();;
            $table->string('out_time')->nullable();;
            $table->string('status')->default(false);;
            $table->integer('factory_id');
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
        Schema::dropIfExists('visitors_tracking');
    }
}
