<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginalDocReceiveDateInImportDocumentShippingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_document_shipping_infos', function (Blueprint $table) {
            $table->date('original_doc_receive_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_document_shipping_infos', function (Blueprint $table) {
            $table->dropColumn('original_doc_receive_date');
        });
    }
}
