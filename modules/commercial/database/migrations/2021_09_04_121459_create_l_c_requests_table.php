<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLCRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('lc_requests')) {
            Schema::create('lc_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('factory_id');
                $table->unsignedBigInteger('buyer_id');
                $table->string('unique_id')->nullable();
                $table->date('request_date')->nullable();
                $table->date('open_date')->nullable();
                $table->string('attention')->nullable();
                $table->string('approve_status')->nullable();
                $table->string('remarks')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lc_requests');
    }
}
