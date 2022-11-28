<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDipoNoToImportDocumentAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_document_acceptances', function (Blueprint $table) {
            $table->string('dipo_no')->nullable()->after('factory_id');
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
            $table->dropColumn('dipo_no');
        });
    }
}
