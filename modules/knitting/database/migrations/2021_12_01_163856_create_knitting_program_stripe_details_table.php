<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnittingProgramStripeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knitting_program_stripe_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('knitting_program_id');
            $table->string('fabric_description')->nullable();
            $table->string('garments_item')->nullable();
            $table->unsignedBigInteger('fabric_nature_id')->nullable();
            $table->string('fabric_nature')->nullable();
            $table->unsignedBigInteger('item_color_id')->nullable();
            $table->json('stripe_details')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('knitting_program_stripe_details');
    }
}
