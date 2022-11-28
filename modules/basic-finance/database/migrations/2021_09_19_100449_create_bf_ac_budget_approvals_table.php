<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfAcBudgetApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bf_ac_budget_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bf_ac_budget_id')->constrained('bf_ac_budgets')->cascadeOnDelete();
            $table->foreignId('bf_ac_budget_detail_id')->constrained('bf_ac_budget_details')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('apprv_amount', 15, 4);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('bf_ac_budget_approvals');
    }
}
