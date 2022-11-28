<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivedSewingoutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archived_sewingoutputs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bundle_card_id');
            $table->string('output_challan_no', 25);
            $table->string('challan_no', 25)->nullable();
            $table->unsignedInteger('line_id');
            $table->string('hour', 8)->nullable();
            $table->boolean('status')->default(0);
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('user_id');
            $table->json('details')->nullable();
            $table->unsignedInteger('factory_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('bundle_card_id');
            $table->index('line_id');
            $table->index('purchase_order_id');
            $table->index('color_id');

            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archived_sewingoutputs');
    }
}
