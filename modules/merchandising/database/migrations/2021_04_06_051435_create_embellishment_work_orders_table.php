<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmbellishmentWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('embellishment_work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 20);
            $table->unsignedBigInteger('factory_id');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('buyer_id');
            $table->date('booking_date');
            $table->date('delivery_date')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedTinyInteger('pay_mode')->nullable()
                ->comment('1 => Credit, 2 => Import, 3 => In House, 4 => Within Group');
            $table->unsignedTinyInteger('source')->nullable()
                ->comment('1 => Abroad, 2 => Epz, 3 => Non-Epz');
            $table->string('exchange_rate')->nullable();
            $table->string('currency', 20)->nullable();
            $table->string('attention')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('is_short')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('embellishment_work_orders');
    }
}
