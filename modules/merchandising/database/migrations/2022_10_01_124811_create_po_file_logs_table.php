<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoFileLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_file_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('po_file_id');
            $table->string('style');
            $table->string('po_no');
            $table->json('quantity_matrix');
            $table->text('remarks');
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
        Schema::dropIfExists('po_file_logs');
    }
}
