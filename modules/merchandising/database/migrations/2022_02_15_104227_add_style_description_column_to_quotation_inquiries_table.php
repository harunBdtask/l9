<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStyleDescriptionColumnToQuotationInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_inquiries', function (Blueprint $table) {
            $table->text('style_description')->nullable()->after('style_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotation_inquiries', function (Blueprint $table) {
            $table->dropColumn('style_description');
        });
    }
}
