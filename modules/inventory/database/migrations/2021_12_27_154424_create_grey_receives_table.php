<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGreyReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grey_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->nullable();
            $table->string('factory_name')->nullable();
            $table->unsignedInteger('source_id')->nullable();
            $table->string('source_name')->nullable();
            $table->unsignedInteger('received_type_id')->nullable();
            $table->string('received_type_value')->nullable();
            $table->string('challan_no');
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
        Schema::dropIfExists('grey_receives');
    }
}
