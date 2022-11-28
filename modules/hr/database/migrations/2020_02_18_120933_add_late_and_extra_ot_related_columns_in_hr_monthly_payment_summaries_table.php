<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLateAndExtraOtRelatedColumnsInHrMonthlyPaymentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_monthly_payment_summaries', function (Blueprint $table) {
            $table->integer('total_late')->after('total_absent_day')->nullable();

            $table->time('total_regular_extra_ot_hour_time')->after('total_ot_amount')->nullable();
            $table->double('total_regular_extra_ot_hour')->after('total_regular_extra_ot_hour_time')->nullable();
            $table->double('total_regular_extra_ot_minute')->after('total_regular_extra_ot_hour')->nullable();
            $table->time('total_regular_unapproved_extra_ot_hour_time')->after('total_regular_extra_ot_minute')->nullable();
            $table->double('total_regular_unapproved_extra_ot_hour')->after('total_regular_unapproved_extra_ot_hour_time')->nullable();
            $table->double('total_regular_unapproved_extra_ot_minute')->after('total_regular_unapproved_extra_ot_hour')->nullable();

            $table->time('total_night_unapproved_ot_hour_time')->after('night_ot_amount')->nullable();
            $table->double('total_night_unapproved_ot_hour')->after('total_night_unapproved_ot_hour_time')->nullable();
            $table->double('total_night_unapproved_ot_minute')->after('total_night_unapproved_ot_hour')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_monthly_payment_summaries', function (Blueprint $table) {
            $table->dropColumn([
                'total_late',
                'total_regular_extra_ot_hour_time',
                'total_regular_extra_ot_hour',
                'total_regular_extra_ot_minute',
                'total_regular_unapproved_extra_ot_hour_time',
                'total_regular_unapproved_extra_ot_hour',
                'total_regular_unapproved_extra_ot_minute',
            ]);
        });
    }
}
