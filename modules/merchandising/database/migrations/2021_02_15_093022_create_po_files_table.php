<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("buyer_id");
            $table->string("po_no");
            $table->string("file");
            $table->boolean("processed")->default(0);
            $table->boolean("used")->default(0);
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
        Schema::dropIfExists('po_files');
    }
}
