<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechPackFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tech_pack_files', function (Blueprint $table) {
            $table->id();
            $table->string("style");
            $table->string("file");
            $table->string("creeper_count");
            $table->string("body_part_count");
            $table->boolean("processed")->default(0);
            $table->boolean("used")->default(0);
            $table->json("contents")->nullable();
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
        Schema::dropIfExists('tech_pack_files');
    }
}
