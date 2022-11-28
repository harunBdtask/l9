<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScanTimeRelatatedColumnsInArchivedBundleCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archived_bundle_cards', function (Blueprint $table) {
            $table->dateTime('print_embr_send_scan_time')->after('cutting_date')->nullable();
            $table->dateTime('print_embr_received_scan_time')->after('print_sent_date')->nullable();
            $table->dateTime('input_scan_time')->after('embroidary_received_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archived_bundle_cards', function (Blueprint $table) {
            $table->dropColumn([
                'print_embr_send_scan_time',
                'print_embr_received_scan_time',
                'input_scan_time',
            ]);
        });
    }
}
