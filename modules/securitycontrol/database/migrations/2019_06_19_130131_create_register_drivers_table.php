<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factory_id');
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('license_no');
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(false);
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
        Schema::dropIfExists('register_drivers');
    }
}
