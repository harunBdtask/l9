<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrOtApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_ot_approvals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('ot_date');
            $table->time('ot_start_time');
            $table->time('ot_end_time');
            $table->unsignedInteger('ot_for')->nullable()->comment = '1 = general, 2 = shipment';
            $table->text('file')->nullable();
            $table->text('approved_by')->nullable();
            $table->text('remarks')->nullable();

            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('hr_ot_approvals');
    }
}
