<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('booking_id');
            $table->string('booking_no')->nullable();
            $table->string('uniq_id')->nullable();
            $table->string('order_number')->nullable();
            $table->date('allocation_date');

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
        Schema::dropIfExists('yarn_allocations');
    }
}
