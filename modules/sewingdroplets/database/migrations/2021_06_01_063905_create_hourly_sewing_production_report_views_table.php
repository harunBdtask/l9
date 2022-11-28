<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourlySewingProductionReportViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            create view `hourly_sewing_production_report_views` AS 
            SELECT 
                reports.*, 
                floors.floor_no floor,
                sewing_lines.line_no line_no, 
                buyers.name buyer, 
                orders.style_name order_no, 
                orders.job_no job_no,
                purchase_orders.po_no po,
                orders.smv smv, 
                colors.name 
            FROM `hourly_sewing_production_reports` reports
                INNER JOIN floors on reports.floor_id = floors.id
                INNER JOIN `lines` sewing_lines on reports.line_id = sewing_lines.id 
                INNER JOIN buyers on reports.buyer_id = buyers.id 
                INNER JOIN orders on reports.order_id = orders.id 
                INNER JOIN purchase_orders on reports.purchase_order_id = purchase_orders.id 
                INNER JOIN colors on reports.color_id = colors.id 
            WHERE reports.production_date = CURDATE();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS hourly_sewing_production_report_views;");
    }
}
