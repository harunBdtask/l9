<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTumblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tumbles', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id');
            $table->enum('entry_basis', [1, 2]);
            $table->unsignedInteger('dyeing_batch_id')->nullable();
            $table->string('dyeing_batch_no', 40)->nullable();
            $table->unsignedInteger('textile_order_id')->nullable();
            $table->string('textile_order_no', 40)->nullable();
            $table->date('production_date')->nullable();
            $table->date('streaming_date')->nullable();
            $table->unsignedInteger('shift_id')->nullable();
            $table->date('dry_date')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('tumbles');
    }
}
