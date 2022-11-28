<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DyesChemicalsIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyes_chemicals_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dyes_chemicals_receive_id')->constrained('dyes_chemicals_receive')->cascadeOnDelete();
            $table->string('to')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->date('delivery_date');
            $table->string('requisition')->nullable();
            $table->json('details')->nullable();
            $table->tinyInteger('readonly')->default(1);
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
        Schema::dropIfExists('dyes_chemicals_issues');
    }
}
