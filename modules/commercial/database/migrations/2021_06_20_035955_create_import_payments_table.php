<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_document_acceptance_id');
            $table->date('payment_date')->nullable();
            $table->tinyInteger('payment_head_id')->nullable()
                ->comment('1=IFDBC Liability, 2=Bank Charge, 3=Interest');

            $table->tinyInteger('adj_source_id')->nullable();
            $table->string('conversion_rate', 30)->nullable();
            $table->string('accepted_amount', 30)->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('import_payments');
    }
}
