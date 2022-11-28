<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditStatusColumnToTechPackFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tech_pack_files', function (Blueprint $table) {
            $table->string('edit_status')->nullable()->after('processed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tech_pack_files', function (Blueprint $table) {
            $table->dropColumn('edit_status');
        });
    }
}
