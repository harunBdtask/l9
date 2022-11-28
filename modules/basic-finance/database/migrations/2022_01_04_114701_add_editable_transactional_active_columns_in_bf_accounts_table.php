<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditableTransactionalActiveColumnsInBfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_accounts', function (Blueprint $table) {
            $table->tinyInteger('is_editable')->default(1)->after('parent_ac')->comment("0=No,1=Yes");
            $table->tinyInteger('is_transactional')->default(1)->after('is_editable')->comment("0=No,1=Yes");
            $table->tinyInteger('is_active')->default(1)->after('is_transactional')->comment("0=No,1=Yes");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'is_editable',
                'is_transactional',
                'is_active',
            ]);
        });
    }
}
