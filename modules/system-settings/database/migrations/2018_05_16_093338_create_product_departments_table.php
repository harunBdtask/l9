<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_department');
            $table->integer('status')->default(1)->comment = "1=Active,2=In Active,3=Cancelled";
            $table->unsignedInteger('factory_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->integer('is_deleted')->default(0)->comment = "0=Not Deleted,1=Deleted";
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('product_departments');
    }
}
