<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNightOtRelatedColumnsInHrMonthlyPaymentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_monthly_payment_summaries', function (Blueprint $table) {
            $table->time('night_ot_hour_time')->nullable()->after('generated_by');
            $table->double('night_ot_hour')->nullable()->after('night_ot_hour_time');
            $table->double('night_ot_minute')->nullable()->after('night_ot_hour');
            $table->double('night_ot_rate')->nullable()->after('night_ot_minute');
            $table->double('night_ot_amount')->nullable()->after('night_ot_rate');
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
               'night_ot_hour_time',
               'night_ot_hour',
               'night_ot_minute',
               'night_ot_rate',
               'night_ot_amount',
           ]);
        });
    }
}
