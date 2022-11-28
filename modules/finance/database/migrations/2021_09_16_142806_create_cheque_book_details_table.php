<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChequeBookDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque_book_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cheque_book_id')->constrained('cheque_books')->cascadeOnDelete();
            $table->string('cheque_no');
            $table->string('paid_to')->nullable();
            $table->decimal('amount')->nullable();
            $table->date('cheque_date')->nullable();
            $table->date('cheque_due_date')->nullable();
            $table->tinyInteger('status')->nullable()
                ->comment('1 = Active, 2 = In-Active, 3 = Paid, 4 = Hold, 5 = Scrip');
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
        Schema::dropIfExists('cheque_book_details');
    }
}
