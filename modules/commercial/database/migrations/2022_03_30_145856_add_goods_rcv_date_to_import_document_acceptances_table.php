<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoodsRcvDateToImportDocumentAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_document_acceptances', function (Blueprint $table) {
            $table->date('goods_rcv_date')->nullable()->after('dipo_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_document_acceptances', function (Blueprint $table) {
            $table->dropColumn('goods_rcv_date');
        });
    }
}
