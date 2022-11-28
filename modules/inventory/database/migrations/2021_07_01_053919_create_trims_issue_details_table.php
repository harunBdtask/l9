<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_issue_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('trims_issue_id');
            $table->unsignedInteger('trims_receive_id');
            $table->unsignedInteger('trims_receive_detail_id');
            $table->string('order_no', 30)->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('item_description')->nullable();
            $table->string('brand_sup_ref')->nullable();
            $table->string('item_color', 30)->nullable();
            $table->string('item_size', 30)->nullable();
            $table->unsignedInteger('uom_id');
            $table->string('issue_qty', 20)->nullable();

            $table->string('floor', 20)->nullable();
            $table->string('room', 20)->nullable();
            $table->string('rack', 20)->nullable();
            $table->string('shelf', 20)->nullable();
            $table->string('bin', 20)->nullable();

            $table->unsignedInteger('sewing_line_no')->nullable();

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
        Schema::dropIfExists('trims_issue_details');
    }
}
