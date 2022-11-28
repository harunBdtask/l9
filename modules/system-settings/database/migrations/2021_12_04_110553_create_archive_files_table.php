<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchiveFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->string('file_name');
            $table->string('file');
            $table->softDeletes();
            $table->unsignedInteger('deleted_by');
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
        Schema::dropIfExists('archive_files');
    }
}
