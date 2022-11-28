<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('machine_no', 20);
            $table->string('machine_code', 3)->nullable();
            $table->integer('machine_type')->nullable()->comment = "1=dyeing,2=knitting";
            $table->string('machine_name', 14)->nullable();
            $table->float('machine_rpm')->nullable();
            $table->float('machine_dia')->default(0);
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('machines');
    }
}
