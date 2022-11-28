<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCumulativeAcceptedValueFieldInImportDocumentPiInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_document_pi_infos', function (Blueprint $table) {
            $table->string('cumulative_accepted_value', 20)
                ->nullable()
                ->after('mrr_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_document_pi_infos', function (Blueprint $table) {
            $table->dropColumn('cumulative_accepted_value');
        });
    }
}
