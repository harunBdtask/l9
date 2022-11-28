<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('factories')) {
            Schema::create('factories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('group_name', 60);
                $table->string('factory_name', 50);
                $table->string('factory_short_name', 15)->unique();
                $table->string('factory_address', 60);
                $table->string('responsible_person', 60)->nullable();
                $table->string('phone_no', 50)->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factories');
    }
}
