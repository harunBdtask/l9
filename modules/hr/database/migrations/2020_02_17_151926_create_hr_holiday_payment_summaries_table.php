<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrHolidayPaymentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_holiday_payment_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userid');
            $table->date('pay_month')->nullable();
            $table->integer('total_working_holiday')->nullable();
            $table->time('total_working_hour_time')->nullable();
            $table->double('total_working_hour')->nullable();
            $table->double('total_working_minute')->nullable();
            $table->double('payment_rate',11,4)->nullable();
            $table->double('total_payable_amount',11,4)->nullable();
            $table->tinyInteger('paid_status')->default(0)->comment="0=no,1=yes";
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
        Schema::dropIfExists('hr_holiday_payment_summaries');
    }
}
