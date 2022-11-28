<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnReceiveReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_receive_returns', function (Blueprint $table) {
            $table->id();
            $table->string('receive_return_no', 20)->comment('Auto Generated No')->nullable();
            $table->unsignedInteger('receive_id');
            $table->unsignedInteger('factory_id');
            $table->date('return_date');
            $table->unsignedInteger('return_to');
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
        Schema::dropIfExists('yarn_receive_returns');
    }
}
