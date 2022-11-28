<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleCardGenerationCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_card_generation_caches', function (Blueprint $table) {
            $table->id();
            $table->integer('bg_id')->index()->comment("bundle_card_generation_details_id");
            $table->integer('sid')->index();
            $table->json('details');
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
        Schema::dropIfExists('bundle_card_generation_caches');
    }
}
