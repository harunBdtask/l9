<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentStoreToStores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ds_stores', function (Blueprint $table) {
            $table->foreignId('parent')->nullable()->after('description')->constrained('ds_stores', 'id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ds_stores', function (Blueprint $table) {
            $table->dropForeign('ds_stores_parent_foreign');
            $table->dropColumn('parent');
        });
    }
}
