<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrMonthlyPaymentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_monthly_payment_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userid');
            $table->date('pay_month')->nullable();
            $table->integer('total_working_day')->nullable();
            $table->integer('total_weekend')->nullable()->comment="Friday";
            $table->integer('total_festival_day')->nullable()->comment="Festival days";
            $table->integer('total_other_holiday')->nullable()->comment="Holidays table";
            $table->integer('total_holiday')->nullable()->comment="Friday + Festival days + Holidays table";
            $table->integer('total_present_day')->nullable();
            $table->integer('total_absent_day')->nullable();
            $table->integer('total_leave')->nullable();
            $table->integer('total_payable_days')->nullable();
            $table->double('basic_salary',11,4)->nullable();
            $table->double('house_rent',11,4)->nullable();
            $table->double('medical_allowance',11,4)->nullable();
            $table->double('transport_allowance',11,4)->nullable();
            $table->double('food_allowance',11,4)->nullable();
            $table->double('attendance_bonus',11,4)->nullable();
            $table->double('gross_salary',11,4)->nullable();
            $table->time('ot_hour_time')->nullable();
            $table->double('ot_hour')->nullable();
            $table->double('ot_minute')->nullable();
            $table->double('ot_rate',11,4)->nullable();
            $table->double('total_ot_amount',11,4)->nullable();
            $table->double('absent_deduction',11,4)->nullable();
            $table->double('attendance_bonus_deduction',11,4)->nullable();
            $table->double('revenue_stamp',11,4)->nullable();
            $table->double('total_payable_amount',11,4)->nullable();
            $table->tinyInteger('already_paid_status')->default(0)->comment="0=no,1=yes";
            $table->date('pay_slip_generate_date')->nullable();
            $table->date('salary_sheet_generate_date')->nullable();
            $table->unsignedInteger('generated_by')->nullable();
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
        Schema::dropIfExists('hr_monthly_payment_summaries');
    }
}
